import { ThreeDots } from 'react-loader-spinner'
import { Box } from '@mui/material'

function LoadingSpinner() {
  return (
    <Box
      sx={{
        marginTop: 8, display: 'flex', flexDirection: 'column', alignItems: 'center',
      }}>
      <ThreeDots
        visible={true}
        height="80"
        width="80"
        color= '#19857b'
        radius="9"
        ariaLabel="three-dots-loading"
        wrapperStyle={{}}
        wrapperClass=""
      />
    </Box>
  )
}

export default LoadingSpinner
