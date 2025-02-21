import React, { useState } from 'react'
import styles from './WalletForm.module.css'
import api, { parseError, parseErrors } from '../../Api'
import { AlertError, AlertSuccess } from '../../Alert'
import { ButtonRow, InputError, InputLabel, InputRow } from '../../Form'
import System from 'src/Layout/System'
import { Link, Navigate } from 'react-router-dom'
import { useAuth } from 'src/OAuth/Provider'

export default function WalletForm(): React.JSX.Element {
  const { isAuthenticated, getToken } = useAuth()

  if (!isAuthenticated) {
    return <Navigate to="/" replace />
  }

  const [formData, setFormData] = useState({
    name: '',
    currency: '',
    initial_balance: 0,
  })

  const [buttonActive, setButtonActive] = useState<boolean>(true)
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [error, setError] = useState<string | null>(null)
  const [success, setSuccess] = useState<string | null>(null)

  const handleChange = (event: React.FormEvent<HTMLInputElement>) => {
    const input = event.currentTarget
    setFormData({
      ...formData,
      [input.name]: input.type === 'checkbox' ? input.checked : input.value,
    })
  }

  const handleSubmit = async (event: React.SyntheticEvent) => {
    event.preventDefault()

    setButtonActive(false)
    setErrors({})
    setError(null)
    setSuccess(null)

    api
      .post(
        '/v1/auth/finance/wallet',
        {
          name: formData.name,
          currency: formData.currency,
          initial_balance: formData.initial_balance,
        },
        {
          Authorization: await getToken(),
        },
      )
      .then(() => {
        setSuccess('Wallet created!')
        setButtonActive(true)
      })
      .catch(async (error) => {
        setErrors(await parseErrors(error))
        setError(await parseError(error))
        setButtonActive(true)
      })
  }

  return (
    <System>
      <div data-testid="wallet-form" className={styles.walletForm}>
        <AlertError message={error} />
        <AlertSuccess message={success} />

        {!success ? (
          <form className="form" method="post" onSubmit={handleSubmit}>
            <InputRow error={errors.name}>
              <InputLabel htmlFor="name" label="Wallet name" />
              <input
                id="name"
                name="name"
                type="text"
                value={formData.name}
                onChange={handleChange}
                required
              />
              <InputError error={errors.email} />
            </InputRow>
            <InputRow error={errors.currency}>
              <InputLabel htmlFor="currency" label="Currency" />
              <input
                id="currency"
                name="currency"
                type="text"
                value={formData.currency}
                onChange={handleChange}
                required
              />
              <InputError error={errors.currency} />
            </InputRow>
            <InputRow error={errors.initial_balance}>
              <InputLabel htmlFor="initial_balance" label="Initial balance" />
              <input name="initial_balance" type="number" onChange={handleChange} required />
              <InputError error={errors.initial_balance} />
            </InputRow>
            <ButtonRow>
              <button type="submit" data-testid="save-wallet-button" disabled={!buttonActive}>
                Save wallet
              </button>
            </ButtonRow>
          </form>
        ) : null}
      </div>
      <p>
        <Link to="/">Back to Home</Link>
      </p>
    </System>
  )
}
