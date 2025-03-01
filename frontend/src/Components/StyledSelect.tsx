import { Select, styled } from '@mui/material'

const StyledSelect = styled(Select)(({ theme }) => ({
  width: '100%',
  margin: '8px 0',
  borderRadius: '4px',
  backgroundColor: theme.palette.background.paper,
  color: theme.palette.text.primary,
  '& .MuiOutlinedInput-notchedOutline': {
    borderColor: theme.palette.grey[800],
  },
  '&:focus': {
    '& .MuiOutlinedInput-notchedOutline': {
      borderColor: theme.palette.primary.main,
    },
  },
}))

export default StyledSelect
