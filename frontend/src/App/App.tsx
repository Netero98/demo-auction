import React, { useMemo } from 'react'
import './App.css'
import { FeaturesProvider } from '../FeatureToggle'
import { BrowserRouter, Route, Routes } from 'react-router-dom'
import Guest from '../Pages/Guest'
import Join from '../Pages/Join'
import { NotFound } from '../Components/Error'
import Confirm from 'src/Pages/Join/Confirm'
import Success from 'src/Pages/Join/Success'
import OAuth from '../Pages/OAuth'
import { AuthProvider } from 'src/Pages/OAuth/Provider'
import Dashboard from '../Pages/Dashboard'
import { Box, createTheme, CssBaseline, ThemeProvider } from '@mui/material'
import { themeSettings } from 'src/theme'
import GlobalStateProvider from 'src/Provider/State/GlobalStateProvider'

export default function App({ features }: { features: string[] }) {
  const theme = useMemo(() => createTheme(themeSettings), [])

  return (
    <div className="app">
      <FeaturesProvider features={features}>
        <AuthProvider
          authorizeUrl={process.env.REACT_APP_AUTH_URL + '/authorize'}
          tokenUrl={process.env.REACT_APP_AUTH_URL + '/token'}
          clientId="frontend"
          scope="common"
          redirectPath="/oauth"
        >
          <GlobalStateProvider>
            <BrowserRouter>
              <ThemeProvider theme={theme}>
                <CssBaseline />
                <Box width="100%" height="100%" padding="1rem 2rem 4rem 2rem">
                  <Routes>
                    <Route path="/" element={<Dashboard />} />
                    <Route path="/oauth" element={<OAuth />} />
                    <Route path="/join" element={<Join />} />
                    <Route path="/join/confirm" element={<Confirm />} />
                    <Route path="/join/success" element={<Success />} />
                    <Route path="/guest" element={<Guest />} />
                    <Route path="*" element={<NotFound />} />
                  </Routes>
                </Box>
              </ThemeProvider>
            </BrowserRouter>
          </GlobalStateProvider>
        </AuthProvider>
      </FeaturesProvider>
    </div>
  )
}
