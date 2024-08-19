import { Box, Typography } from '@mui/material'

function Profile() {
  return (
    <Box component='section' sx={{ my: 5, textAlign: 'center' }}>
      <Typography component="h1" variant="h4" sx={{ color:'#666' }}>You are now Logged in</Typography>
    </Box>
  )
}

export default Profile
