import { styled } from '@mui/material'

const StyledInput = styled('input')(({ theme }) => ({
  width: '100%',
  padding: '8px',
  margin: '8px 0',
  borderRadius: '4px',
  border: `1px solid ${theme.palette.grey[800]}`,
  backgroundColor: theme.palette.background.paper,
  color: theme.palette.text.primary,
  '&:focus': {
    borderColor: theme.palette.primary.main,
    outline: 'none',
  },
}))

export default StyledInput
