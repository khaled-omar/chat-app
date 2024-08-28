import {Avatar, Box, ListItem, ListItemAvatar, Typography} from "@mui/material";

function ChatItem() {
    return (
        <ListItem
            button: true
            sx={{
                display: 'flex',
                alignItems: 'center',
                padding: '10px 15px',
                '&:hover': {
                    backgroundColor: '#f0f0f0',
                },
            }}
        >
            <ListItemAvatar>
                <Avatar alt={name} src={'avatarUrl'} />
            </ListItemAvatar>
            <Box sx={{ flex: 1, ml: 2 }}>
                <Typography variant="subtitle1" sx={{ fontWeight: 'bold' }}>
                    {'User name'}
                </Typography>
                <Typography
                    variant="body2"
                    color="textSecondary"
                    sx={{
                        whiteSpace: 'nowrap',
                        overflow: 'hidden',
                        textOverflow: 'ellipsis',
                    }}
                >
                    {'message'}
                </Typography>
            </Box>
        </ListItem>
    )
}

export default ChatItem
