import React from 'react'
import styles from './Guest.module.css'
import System from 'src/Components/Layout/System'
import { Link, Navigate } from 'react-router-dom'
import { useAuth } from 'src/Pages/OAuth/Provider'

export default function Guest(): React.JSX.Element {
  const { isAuthenticated, login, logout } = useAuth()

  if (isAuthenticated) {
    return <Navigate to="/" replace />
  }

  return (
    <System>
      <h1>Finsly</h1>
      <p>We are here</p>
      <p className={styles.links}>
        {isAuthenticated ? (
          <button type="button" data-testid="logout-button" onClick={() => logout()}>
            Log Out
          </button>
        ) : (
          <>
            <Link to="/join" data-testid="join-link">
              Join
            </Link>
            <button type="button" data-testid="login-button" onClick={() => login({})}>
              Log In
            </button>
          </>
        )}
      </p>
    </System>
  )
}
