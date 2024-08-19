import * as React from 'react'
import Button from '@mui/material/Button'
import Dialog from '@mui/material/Dialog'
import DialogActions from '@mui/material/DialogActions'
import DialogContent from '@mui/material/DialogContent'
import DialogContentText from '@mui/material/DialogContentText'
import DialogTitle from '@mui/material/DialogTitle'
import Slide from '@mui/material/Slide'

const Transition = React.forwardRef(function Transition(props, ref) {
  return <Slide direction="up" ref={ref} {...props} />
})

export default function AlertDialogSlide({ openDialog, setOpenDialog, title, description }) {
  return (
    <React.Fragment>
      <Dialog
        open={openDialog}
        TransitionComponent={Transition}
        keepMounted
        onClose={() => setOpenDialog(false)}
        aria-describedby="alert-dialog-slide-description"
      >
        <DialogTitle>{title}</DialogTitle>
        <DialogContent>
          <DialogContentText
            id="alert-dialog-slide-description">{description}</DialogContentText>
        </DialogContent>
        <DialogActions>
          <Button onClick={() => setOpenDialog(false)}>Agree</Button>
        </DialogActions>
      </Dialog>
    </React.Fragment>
  )
}
