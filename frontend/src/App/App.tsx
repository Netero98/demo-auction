import React from 'react'
import './App.css'
import { FeaturesProvider } from '../FeatureToggle'
import { BrowserRouter, Route, Routes } from 'react-router-dom'
import Home from '../Home'
import Join from '../Join'
import { NotFound } from '../Error'
import Confirm from '../Join/Confirm'
import Success from '../Join/Success'
import OAuth from '../OAuth'
import { AuthProvider } from '../OAuth/Provider'
import Dashboard from '../Pages/Dashboard'
import WalletForm from 'src/Pages/WalletForm/WalletForm'

export default function App({ features }: { features: string[] }) {
  return (
    <FeaturesProvider features={features}>
      <AuthProvider
        authorizeUrl={process.env.REACT_APP_AUTH_URL + '/authorize'}
        tokenUrl={process.env.REACT_APP_AUTH_URL + '/token'}
        clientId="frontend"
        scope="common"
        redirectPath="/oauth"
      >
        <BrowserRouter>
          <div className="app">
            <Routes>
              <Route path="/" element={<Home />} />
              <Route path="/oauth" element={<OAuth />} />
              <Route path="/join" element={<Join />} />
              <Route path="/join/confirm" element={<Confirm />} />
              <Route path="/join/success" element={<Success />} />
              <Route path="/finance/dashboard" element={<Dashboard />} />
              <Route path="/finance/wallet-form" element={<WalletForm />} />
              <Route path="*" element={<NotFound />} />
            </Routes>
          </div>
        </BrowserRouter>
      </AuthProvider>
    </FeaturesProvider>
  )
}
