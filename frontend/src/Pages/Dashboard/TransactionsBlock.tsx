import BoxHeader from 'src/Components/BoxHeader'
import DashboardBox from 'src/Components/DashboardBox'
import { Box, useTheme, InputLabel, Modal, Typography, MenuItem, FormControl } from '@mui/material'
import { DataGrid, GridColDef, GridRenderCellParams, GridCellParams } from '@mui/x-data-grid'
import React, { useState } from 'react'
import { AlertError } from 'src/Components/Alert'
import { ButtonRow, InputError, InputRow } from 'src/Components/Form'
import { useAuth } from 'src/Pages/OAuth/Provider'
import api, { parseError, parseErrors } from 'src/Api'
import StyledButton from 'src/Components/StyledButton'
import StyledSelect from 'src/Components/StyledSelect'
import StyledInput from 'src/Components/StyledInput'
import Transaction from 'src/State/Model/Transaction'
import useGlobalState from 'src/Provider/State/useGlobalState'
import Category from 'src/State/Model/Category'

const TransactionsBlock = (): React.JSX.Element => {
  const { getToken } = useAuth()
  const { palette } = useTheme()
  const [formData, setFormData] = useState({
    transaction_wallet_id: '',
    transaction_amount: 0,
    transaction_category_id: '',
    transaction_description: '',
  })
  const [buttonActive, setButtonActive] = useState<boolean>(true)
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [error, setError] = useState<string | null>(null)
  const [openModal, setOpenModal] = useState<boolean>(false)
  const globalState = useGlobalState()

  const handleChange = (
    event: React.ChangeEvent<HTMLInputElement | { name?: string; value: unknown }>,
  ) => {
    const name = event.target.name as string
    const value = event.target.value

    setFormData({
      ...formData,
      [name]: value,
    })
  }

  const handleSubmit = async (event: React.SyntheticEvent) => {
    event.preventDefault()

    setButtonActive(false)
    setErrors({})
    setError(null)

    api
      .post(
        '/v1/finance/transaction',
        {
          wallet_id: formData.transaction_wallet_id,
          amount: formData.transaction_amount,
          category_id: formData.transaction_category_id,
          description: formData.transaction_description || null,
        },
        {
          Authorization: await getToken(),
        },
      )
      .then(() => {
        setButtonActive(true)
        globalState.fetchTransactions()
        setOpenModal(false) // Close modal after successful addition
        // Reset form
        setFormData({
          transaction_wallet_id: '',
          transaction_amount: 0,
          transaction_category_id: '',
          transaction_description: '',
        })
      })
      .catch(async (error) => {
        setErrors(await parseErrors(error))
        setError(await parseError(error))
        setButtonActive(true)
      })
  }

  // Find wallet and category names for display
  const getWalletName = (walletId: string) => {
    if (!globalState.wallets) return '—'
    const wallet = globalState.wallets.find((w) => w.id === walletId)
    return wallet ? wallet.name : '—'
  }

  const getCategoryName = (categoryId: string) => {
    if (!globalState.categories) return '—'
    const category = globalState.categories.find((c) => c.id === categoryId)
    return category ? category.name : '—'
  }

  // Custom type for valueFormatter params
  interface ValueFormatterParams {
    value: string | number | null | undefined
  }

  const transactionColumns: GridColDef<Transaction>[] = [
    {
      field: 'created_at',
      headerName: 'Date',
      flex: 0.5,
      valueFormatter: (params: ValueFormatterParams) => {
        if (params.value) {
          return new Date(params.value as string).toLocaleDateString()
        }
        return '—'
      },
    },
    {
      field: 'wallet_id',
      headerName: 'Wallet',
      flex: 0.5,
      renderCell: (params: GridRenderCellParams<Transaction>) => {
        return getWalletName(params.row.wallet_id)
      },
    },
    {
      field: 'category_id',
      headerName: 'Category',
      flex: 0.5,
      renderCell: (params: GridRenderCellParams<Transaction>) => {
        return getCategoryName(params.row.category_id)
      },
    },
    {
      field: 'amount',
      headerName: 'Amount',
      flex: 0.3,
      type: 'number',
      valueFormatter: (params: ValueFormatterParams) => {
        if (params.value !== undefined && params.value !== null) {
          const value = params.value as number
          return value >= 0 ? `+${value}` : `${value}`
        }
        return '0'
      },
      cellClassName: (params: GridCellParams) => {
        const value = params.value as number
        return value !== undefined && value >= 0 ? 'positive-amount' : 'negative-amount'
      },
    },
    {
      field: 'description',
      headerName: 'Description',
      flex: 1,
      type: 'string',
    },
  ]

  return (
    <DashboardBox gridArea="c">
      <>
        <BoxHeader title="Transactions" sideText="" />
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
            '& .positive-amount': {
              color: 'green',
            },
            '& .negative-amount': {
              color: 'red',
            },
          }}
        >
          <StyledButton
            onClick={() => setOpenModal(true)}
            data-testid="button_add_transaction"
            style={{ color: palette.grey[800], marginBottom: '1rem' }}
          >
            Add Transaction
          </StyledButton>
          <DataGrid
            columnHeaderHeight={25}
            rowHeight={35}
            hideFooter={true}
            rows={globalState.transactions || []}
            columns={transactionColumns}
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
            Add New Transaction
          </Typography>
          <div data-testid="transaction-form">
            <AlertError message={error} />
            <form className="form" method="post" onSubmit={handleSubmit}>
              <InputRow error={errors.wallet_id}>
                <InputLabel
                  htmlFor="wallet_id"
                  component="label"
                  style={{ color: palette.grey[800] }}
                >
                  Wallet
                </InputLabel>
                <FormControl fullWidth>
                  <StyledSelect
                    id="transaction_wallet_id"
                    name="transaction_wallet_id"
                    value={formData.transaction_wallet_id}
                    onChange={(event) =>
                      handleChange(
                        event as React.ChangeEvent<
                          HTMLInputElement | { name?: string; value: unknown }
                        >,
                      )
                    }
                    required
                  >
                    <MenuItem value="">Select wallet</MenuItem>
                    {globalState.wallets?.map((wallet) => (
                      <MenuItem key={wallet.id} value={wallet.id}>
                        {wallet.name} ({wallet.currency})
                      </MenuItem>
                    ))}
                  </StyledSelect>
                </FormControl>
                <InputError error={errors.wallet_id} />
              </InputRow>

              <InputRow error={errors.category_id}>
                <InputLabel
                  htmlFor="category_id"
                  component="label"
                  style={{ color: palette.grey[800] }}
                >
                  Category
                </InputLabel>
                <FormControl fullWidth>
                  <StyledSelect
                    id="transaction_category_id"
                    name="transaction_category_id"
                    value={formData.transaction_category_id}
                    onChange={(event) =>
                      handleChange(
                        event as React.ChangeEvent<
                          HTMLInputElement | { name?: string; value: unknown }
                        >,
                      )
                    }
                    required
                  >
                    <MenuItem value="">Select category</MenuItem>
                    {globalState.categories?.map((category: Category) => (
                      <MenuItem key={category.id} value={category.id}>
                        {category.name}
                      </MenuItem>
                    ))}
                  </StyledSelect>
                </FormControl>
                <InputError error={errors.category_id} />
              </InputRow>

              <InputRow error={errors.amount}>
                <InputLabel htmlFor="amount" style={{ color: palette.grey[800] }}>
                  Amount (use negative for expenses, positive for income)
                </InputLabel>
                <StyledInput
                  id="transaction_amount"
                  name="transaction_amount"
                  type="number"
                  value={formData.transaction_amount}
                  onChange={handleChange}
                  required
                />
                <InputError error={errors.amount} />
              </InputRow>

              <InputRow error={errors.description}>
                <InputLabel
                  htmlFor="description"
                  component="label"
                  style={{ color: palette.grey[800] }}
                >
                  Description (optional)
                </InputLabel>
                <StyledInput
                  id="transaction_description"
                  name="transaction_description"
                  type="text"
                  value={formData.transaction_description}
                  onChange={handleChange}
                />
                <InputError error={errors.description} />
              </InputRow>

              <ButtonRow>
                <StyledButton
                  type="submit"
                  data-testid="save-transaction-button"
                  disabled={!buttonActive}
                  style={{ color: palette.grey[800] }}
                >
                  Save transaction
                </StyledButton>
              </ButtonRow>
            </form>
          </div>
        </Box>
      </Modal>
    </DashboardBox>
  )
}

export default TransactionsBlock
