import {Box, Grid} from '@mui/material'


import ChatList from "../components/ChatList.jsx";
import ChatDetails from "../components/ChatDetails.jsx";


function ChatPage() {
    return (
        <Box sx={{height: '100vh', display: 'flex'}}>
            <Grid container spacing={2} >
                <Grid item xs={12} md={3}>
                    <ChatList/>
                </Grid>
                <Grid item  xs={12} md={9} >
                    <ChatDetails/>
                </Grid>
            </Grid>
        </Box>
    );
}

export default ChatPage