import BoxHeader from 'src/Components/BoxHeader'
import DashboardBox from 'src/Components/DashboardBox'
import { useFetchCategories } from 'src/State/Api/useFetchCategories'
import { Box, useTheme, InputLabel, Modal, Typography } from '@mui/material'
import { DataGrid } from '@mui/x-data-grid'
import React, { useEffect, useState } from 'react'
import { AlertError } from 'src/Components/Alert'
import { ButtonRow, InputError, InputRow } from 'src/Components/Form'
import { useAuth } from 'src/Pages/OAuth/Provider'
import api, { parseError, parseErrors } from 'src/Api'
import StyledButton from 'src/Components/StyledButton'
import StyledInput from 'src/Components/StyledInput'

const CategoriesBlock = (): React.JSX.Element => {
  const { getToken } = useAuth()
  const { palette } = useTheme()
  const [formData, setFormData] = useState({
    category_name_input: '',
  })
  const [buttonActive, setButtonActive] = useState<boolean>(true)
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [error, setError] = useState<string | null>(null)
  const [openModal, setOpenModal] = useState<boolean>(false)
  const { categoriesData, fetchCategoriesInitial, fetchCategories } = useFetchCategories()

  useEffect(() => {
    fetchCategoriesInitial().then()
  }, [])

  const handleChange = (event: React.FormEvent<HTMLInputElement>) => {
    const input = event.currentTarget
    setFormData({
      ...formData,
      [input.name]: input.type === 'checkbox' ? input.checked : input.value,
    })
  }

  const handleSubmit = async (event: React.SyntheticEvent) => {
    event.preventDefault()

    setButtonActive(false)
    setErrors({})
    setError(null)

    api
      .post(
        '/v1/finance/category',
        {
          name: formData.category_name_input,
        },
        {
          Authorization: await getToken(),
        },
      )
      .then(() => {
        setButtonActive(true)
        fetchCategories()
        setOpenModal(false) // Close modal after successful addition
        // Reset form
        setFormData({
          category_name_input: '',
        })
      })
      .catch(async (error) => {
        setErrors(await parseErrors(error))
        setError(await parseError(error))
        setButtonActive(true)
      })
  }

  const categoryColumns = [
    {
      field: 'name',
      headerName: 'Category name',
      flex: 1,
    },
    {
      field: 'created_at',
      headerName: 'Created',
      flex: 0.5,
      valueFormatter: (params: { value: string | undefined }) => {
        return params.value ? new Date(params.value).toLocaleDateString() : ''
      },
    },
  ]

  return (
    <DashboardBox gridArea="b">
      <>
        <BoxHeader title="Categories" sideText="" />
        <Box
          mt="1rem"
          p="0 0.5rem"
          height="80%"
          sx={{
            '& .MuiDataGrid-root': {
              color: palette.grey[300],
              border: 'none',
            },
            '& .MuiDataGrid-cell': {
              borderBottom: `1px solid ${palette.grey[800]} !important`,
            },
            '& .MuiDataGrid-columnHeaders': {
              borderBottom: `1px solid ${palette.grey[800]} !important`,
            },
            '& .MuiDataGrid-columnSeparator': {
              visibility: 'hidden',
            },
            '& .form': {
              display: 'flex',
              flexDirection: 'column',
              gap: '1rem',
            },
          }}
        >
          <StyledButton
            onClick={() => setOpenModal(true)}
            data-testid="button_add_category"
            style={{ color: palette.grey[800], marginBottom: '1rem' }}
          >
            Add Category
          </StyledButton>
          <DataGrid
            columnHeaderHeight={25}
            rowHeight={35}
            hideFooter={true}
            rows={categoriesData || []}
            columns={categoryColumns}
          />
        </Box>
      </>

      <Modal
        open={openModal}
        onClose={() => setOpenModal(false)}
        aria-labelledby="modal-modal-title"
        aria-describedby="modal-modal-description"
      >
        <Box
          sx={{
            position: 'absolute',
            top: '50%',
            left: '50%',
            transform: 'translate(-50%, -50%)',
            width: 400,
            bgcolor: 'background.paper',
            boxShadow: 24,
            p: 4,
            borderRadius: '4px',
          }}
        >
          <Typography id="modal-modal-title" variant="h6" component="h2">
            Add New Category
          </Typography>
          <div data-testid="category-form">
            <AlertError message={error} />
            <form className="form" method="post" onSubmit={handleSubmit}>
              <InputRow error={errors.name}>
                <InputLabel htmlFor="name" component="label" style={{ color: palette.grey[800] }}>
                  Category name
                </InputLabel>
                <StyledInput
                  id="category_name_input"
                  name="category_name_input"
                  type="text"
                  value={formData.category_name_input}
                  onChange={handleChange}
                  required
                />
                <InputError error={errors.name} />
              </InputRow>
              <ButtonRow>
                <StyledButton
                  type="submit"
                  data-testid="save-category-button"
                  disabled={!buttonActive}
                  style={{ color: palette.grey[800] }}
                >
                  Save category
                </StyledButton>
              </ButtonRow>
            </form>
          </div>
        </Box>
      </Modal>
    </DashboardBox>
  )
}

export default CategoriesBlock
