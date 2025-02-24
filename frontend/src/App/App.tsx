import React, { useMemo } from 'react'
import './App.css'
import { FeaturesProvider } from '../FeatureToggle'
import { BrowserRouter, Route, Routes } from 'react-router-dom'
import Guest from '../Pages/Guest'
import Join from '../Join'
import { NotFound } from '../Error'
import Confirm from '../Join/Confirm'
import Success from '../Join/Success'
import OAuth from '../OAuth'
import { AuthProvider } from '../OAuth/Provider'
import Dashboard from '../Pages/Dashboard'
import { Box, createTheme, CssBaseline, ThemeProvider } from '@mui/material'
import { themeSettings } from 'src/theme'

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
        </AuthProvider>
      </FeaturesProvider>
    </div>
  )
}
