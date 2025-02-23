import BoxHeader from 'src/Components/BoxHeader'
import DashboardBox from 'src/Components/DashboardBox'
import { useGetWalletsQuery } from 'src/State/Mock/GetWalletsQuery'
import { Box, useTheme } from '@mui/material'
import { DataGrid } from '@mui/x-data-grid'
import React from 'react'

const GridAWallets = (): React.JSX.Element => {
  const { palette } = useTheme()

  const { data: transactionData } = useGetWalletsQuery()

  const transactionColumns = [
    {
      field: 'name',
      headerName: 'Wallet name',
      flex: 1,
    },
    {
      field: 'balance',
      headerName: 'Balance',
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
        }}
      >
        <DataGrid
          columnHeaderHeight={25}
          rowHeight={35}
          hideFooter={true}
          rows={transactionData || []}
          columns={transactionColumns}
        />
      </Box>
    </DashboardBox>
  )
}

export default GridAWallets
