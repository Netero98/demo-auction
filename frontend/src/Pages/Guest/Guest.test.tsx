import React from 'react'
import { render, screen } from '@testing-library/react'
import Guest from './Guest'
import { FeaturesProvider } from '../../FeatureToggle'
import { MemoryRouter } from 'react-router-dom'
import FakeAuthProvider from 'src/Pages/OAuth/Provider/FakeAuthProvider'

test('renders home', () => {
  render(
    <FakeAuthProvider isAuthenticated={false}>
      <FeaturesProvider features={[]}>
        <MemoryRouter>
          <Guest />
        </MemoryRouter>
      </FeaturesProvider>
    </FakeAuthProvider>,
  )

  expect(screen.queryByText(/We will be here/i)).not.toBeInTheDocument()
  expect(screen.getByText(/We are here/i)).toBeInTheDocument()
})
