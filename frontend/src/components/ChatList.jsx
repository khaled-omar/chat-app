import {Box, Divider, List} from "@mui/material";
import ChatItem from "./ChatItem.jsx";

function ChatList() {
    return (
        <Box sx={{ width: '100%', bgcolor: '#f8f9fa', overflowY: 'auto' }}>

            <List>
                <ChatItem/>
                <Divider variant="inset" component="li"/>
                <ChatItem/>
                <Divider variant="inset" component="li"/>
                <ChatItem/>
                <Divider variant="inset" component="li"/>
                <ChatItem/>
            </List>
        </Box>
    )
}

export default ChatList
