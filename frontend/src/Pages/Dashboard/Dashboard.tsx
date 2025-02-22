import React from 'react'
import { Navigate } from 'react-router-dom'
import { useAuth } from 'src/OAuth/Provider'

export default function Dashboard(): React.JSX.Element {
  const { isAuthenticated } = useAuth()

  if (!isAuthenticated) {
    return <Navigate to="/" replace />
  }

  return <h1>Dashboard!</h1>
}
