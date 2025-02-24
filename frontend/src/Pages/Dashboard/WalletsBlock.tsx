import BoxHeader from 'src/Components/BoxHeader'
import DashboardBox from 'src/Components/DashboardBox'
import { useWallets } from 'src/State/Api/useWallets'
import { Box, useTheme, styled, InputLabel, Modal, Typography } from '@mui/material'
import { DataGrid } from '@mui/x-data-grid'
import React, { useEffect, useState } from 'react'
import api, { parseError, parseErrors } from 'src/Api'
import { AlertError } from 'src/Alert'
import { ButtonRow, InputError, InputRow } from 'src/Form'
import { useAuth } from 'src/OAuth/Provider'

const StyledInput = styled('input')(({ theme }) => ({
  width: '100%',
  padding: '8px',
  margin: '8px 0',
  borderRadius: '4px',
  border: `1px solid ${theme.palette.grey[800]}`,
  backgroundColor: theme.palette.background.paper,
  color: theme.palette.text.primary,
  '&:focus': {
    borderColor: theme.palette.primary.main,
    outline: 'none',
  },
}))

const StyledButton = styled('button')(({ theme }) => ({
  padding: '10px 20px',
  borderRadius: '4px',
  border: 'none',
  backgroundColor: theme.palette.primary.main,
  color: theme.palette.common.white,
  cursor: 'pointer',
  '&:disabled': {
    backgroundColor: theme.palette.grey[500],
    cursor: 'not-allowed',
  },
  '&:hover:not(:disabled)': {
    backgroundColor: theme.palette.primary.dark,
  },
}))

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
  const { data: walletsData, fetchWallets, fetchWalletsInitial } = useWallets()

  useEffect(() => {
    fetchWalletsInitial().then()
  }, [])

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
        fetchWallets()
        setOpenModal(false) // Закрываем модальное окно после успешного добавления
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
          <StyledButton onClick={() => setOpenModal(true)} data-testid="button_add_wallet">
            Add Wallet
          </StyledButton>
          <DataGrid
            columnHeaderHeight={25}
            rowHeight={35}
            hideFooter={true}
            rows={walletsData || []}
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
