import React from 'react'

export default function InputError({ error }: { error: string | null }): React.JSX.Element {
  return (
    <>
      {error ? (
        <div data-testid="violation" style={{ color: 'red' }}>
          {error}
        </div>
      ) : null}
    </>
  )
}
