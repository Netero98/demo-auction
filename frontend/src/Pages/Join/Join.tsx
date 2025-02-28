import React from 'react'
import System from 'src/Components/Layout/System'
import { Link, Navigate } from 'react-router-dom'
import JoinForm from './JoinForm'
import { useAuth } from 'src/Pages/OAuth/Provider'
import AuthExternal from 'src/Pages/OAuth/External/AuthExternal'

export default function Join(): React.JSX.Element {
  const { isAuthenticated } = useAuth()

  if (isAuthenticated) {
    return <Navigate to="/" replace />
  }

  return (
    <System>
      <h1>Join to Us</h1>
      <JoinForm />
      <AuthExternal />
      <p>
        <Link to="/">Back to Home</Link>
      </p>
    </System>
  )
}
