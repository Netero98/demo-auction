import BoxHeader from 'src/Components/BoxHeader'
import DashboardBox from 'src/Components/DashboardBox'
import { Box, useTheme, InputLabel, Modal, Typography } from '@mui/material'
import { DataGrid } from '@mui/x-data-grid'
import React, { useState } from 'react'
import { AlertError } from 'src/Components/Alert'
import { ButtonRow, InputError, InputRow } from 'src/Components/Form'
import { useAuth } from 'src/Pages/OAuth/Provider'
import api, { parseError, parseErrors } from 'src/Api'
import StyledInput from 'src/Components/StyledInput'
import StyledButton from 'src/Components/StyledButton'
import useGlobalState from 'src/Provider/State/useGlobalState'

const WalletsBlock = (): React.JSX.Element => {
  const { getToken } = useAuth()
  const { palette } = useTheme()
  const [formData, setFormData] = useState({
    wallet_name_input: '',
    wallet_currency_input: '',
    wallet_initial_balance_input: 0,
  })
  const [buttonActive, setButtonActive] = useState<boolean>(true)
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [error, setError] = useState<string | null>(null)
  const [openModal, setOpenModal] = useState<boolean>(false)
  const globalState = useGlobalState()

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

    api
      .post(
        '/v1/finance/wallet',
        {
          name: formData.wallet_name_input,
          currency: formData.wallet_currency_input,
          initial_balance: formData.wallet_initial_balance_input,
        },
        {
          Authorization: await getToken(),
        },
      )
      .then(() => {
        setButtonActive(true)
        setOpenModal(false) // Закрываем модальное окно после успешного добавления
        globalState.fetchWallets()
      })
      .catch(async (error) => {
        setErrors(await parseErrors(error))
        setError(await parseError(error))
        setButtonActive(true)
      })
  }

  const walletColumns = [
    {
      field: 'name',
      headerName: 'Wallet name',
      flex: 1,
    },
    {
      field: 'initial_balance',
      headerName: 'Initial balance',
      flex: 0.35,
    },
    {
      field: 'currency',
      headerName: 'Currency',
      flex: 0.35,
    },
  ]

  return (
    <DashboardBox gridArea="a">
      <>
        <BoxHeader title="Wallets" sideText="" />
        <Box
          mt="1rem"
          p="0 0.5rem"
          height="80%"
          sx={{
            '& .MuiDataGrid-root': {
              color: palette.grey[300],
              border: 'none',
            },
            '& .MuiDataGrid-cell': {
              borderBottom: `1px solid ${palette.grey[800]} !important`,
            },
            '& .MuiDataGrid-columnHeaders': {
              borderBottom: `1px solid ${palette.grey[800]} !important`,
            },
            '& .MuiDataGrid-columnSeparator': {
              visibility: 'hidden',
            },
            '& .form': {
              display: 'flex',
              flexDirection: 'column',
              gap: '1rem',
            },
          }}
        >
          <StyledButton
            onClick={() => setOpenModal(true)}
            data-testid="button_add_wallet"
            style={{ color: palette.grey[800], marginBottom: '1rem' }}
          >
            Add Wallet
          </StyledButton>
          <DataGrid
            columnHeaderHeight={25}
            rowHeight={35}
            hideFooter={true}
            rows={globalState.wallets || []}
            columns={walletColumns}
          />
        </Box>
      </>

      <Modal
        open={openModal}
        onClose={() => setOpenModal(false)}
        aria-labelledby="modal-modal-title"
        aria-describedby="modal-modal-description"
      >
        <Box
          sx={{
            position: 'absolute',
            top: '50%',
            left: '50%',
            transform: 'translate(-50%, -50%)',
            width: 400,
            bgcolor: 'background.paper',
            boxShadow: 24,
            p: 4,
            borderRadius: '4px',
          }}
        >
          <Typography id="modal-modal-title" variant="h6" component="h2">
            Add New Wallet
          </Typography>
          <div data-testid="wallet-form">
            <AlertError message={error} />
            <form className="form" method="post" onSubmit={handleSubmit}>
              <InputRow error={errors.name}>
                <InputLabel htmlFor="name" component="label" style={{ color: palette.grey[800] }}>
                  Wallet name
                </InputLabel>
                <StyledInput
                  id="wallet_name_input"
                  name="wallet_name_input"
                  type="text"
                  value={formData.wallet_name_input}
                  onChange={handleChange}
                  required
                />
                <InputError error={errors.email} />
              </InputRow>
              <InputRow error={errors.currency}>
                <InputLabel
                  htmlFor="currency"
                  component="label"
                  style={{ color: palette.grey[800] }}
                >
                  Currency
                </InputLabel>
                <StyledInput
                  id="wallet_currency_input"
                  name="wallet_currency_input"
                  type="text"
                  value={formData.wallet_currency_input}
                  onChange={handleChange}
                  required
                />
                <InputError error={errors.currency} />
              </InputRow>
              <InputRow error={errors.initial_balance}>
                <InputLabel htmlFor="initial_balance" style={{ color: palette.grey[800] }}>
                  Initial balance
                </InputLabel>
                <StyledInput
                  id="wallet_initial_balance_input"
                  name="wallet_initial_balance_input"
                  type="number"
                  value={formData.wallet_initial_balance_input}
                  onChange={handleChange}
                  required
                />
                <InputError error={errors.initial_balance} />
              </InputRow>
              <ButtonRow>
                <StyledButton
                  type="submit"
                  data-testid="save-wallet-button"
                  disabled={!buttonActive}
                  style={{ color: palette.grey[800] }}
                >
                  Save wallet
                </StyledButton>
              </ButtonRow>
            </form>
          </div>
        </Box>
      </Modal>
    </DashboardBox>
  )
}

export default WalletsBlock
