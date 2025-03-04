import React from 'react'
import System from 'src/Components/Layout/System'
import { Link } from 'react-router-dom'
import { AlertError } from '../../Components/Alert'
import useAuth from './Provider/useAuth'

export default function OAuth(): React.JSX.Element {
  const { error, loading } = useAuth()

  return (
    <System>
      <h1>Auth</h1>
      <AlertError message={error} />
      {loading ? <p>Loading...</p> : null}
      <p>
        <Link to="/">Back to Home</Link>
      </p>
    </System>
  )
}
