import {useState} from "react";

export default function useDialog() {
    const [openDialog, setOpenDialog] = useState(false)
    const [dialogContent, setDialogContent] = useState('')

    return {openDialog, setOpenDialog, dialogContent, setDialogContent}
}
