import { useContext } from 'react'
import GlobalStateContext, { GlobalStateContextValue } from 'src/Provider/State/GlobalStateContext'

export default function useGlobalState(): GlobalStateContextValue {
  const ctx = useContext(GlobalStateContext)

  if (ctx === null) {
    throw new Error('Unable to use auth outside of provider.')
  }

  return ctx
}
