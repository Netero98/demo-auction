import React from 'react'
import { Box, useMediaQuery } from '@mui/material'
import GridAWallets from './GridAWallets'
import { useAuth } from 'src/OAuth/Provider'
import { Navigate } from 'react-router-dom'
import Navbar from 'src/Navbar'

const gridTemplateLargeScreens = `
  "a b c"
  "a b c"
  "a b c"
`

const gridTemplateSmallScreens = `
  "a a a"
  "a a a"
  "a a a"
  "b b b"
  "b b b"
  "b b b"
  "c c c"
  "c c c"
  "c c c"
`

const Dashboard = () => {
  const { isAuthenticated } = useAuth()

  if (!isAuthenticated) {
    return <Navigate to="/guest" replace />
  }

  const isAboveMediumScreens = useMediaQuery('(min-width: 1200px)')
  return (
    <>
      <Navbar />
      <Box
        width="100%"
        height="100%"
        display="grid"
        gap="1.5rem"
        sx={
          isAboveMediumScreens
            ? {
                gridTemplateColumns: 'repeat(3, minmax(370px, 1fr))',
                gridTemplateRows: 'repeat(3, minmax(60px, 1fr))',
                gridTemplateAreas: gridTemplateLargeScreens,
              }
            : {
                gridAutoColumns: '1fr',
                gridAutoRows: '80px',
                gridTemplateAreas: gridTemplateSmallScreens,
              }
        }
      >
        <GridAWallets />
      </Box>
    </>
  )
}

export default Dashboard
