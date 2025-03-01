import { styled } from '@mui/material'

const StyledButton = styled('button')(({ theme }) => ({
  padding: '10px 20px',
  borderRadius: '4px',
  border: 'none',
  backgroundColor: theme.palette.primary.main,
  color: theme.palette.common.white,
  cursor: 'pointer',
  '&:disabled': {
    backgroundColor: theme.palette.grey[500],
    cursor: 'not-allowed',
  },
  '&:hover:not(:disabled)': {
    backgroundColor: theme.palette.primary.dark,
  },
}))

export default StyledButton
