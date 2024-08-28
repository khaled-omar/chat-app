import {Box, Button, Divider, Paper, TextField, Typography} from "@mui/material";
import {useState} from "react";

const messages = [
    {sender: 'You', text: 'Hello worlds'},
    {sender: 'khaled', text: 'Reply to hello world'},
    {sender: 'You', text: 'Hello worlds'},
]

function ChatDetails({chatData}) {

    const [message, setMessage] = useState('');


    const handleSendMessage = () => {
        if (message.trim()) {
            // onSendMessage(message);
            setMessage('');
        }
    };


    // if (!chatData) {
    //     return (
    //         <Box p={5} textAlign={'center'}>
    //             <Typography variant="h6">Select a chat to view details</Typography>
    //         </Box>
    //     );
    // }

    return (
        <Paper
            elevation={0}
            sx={{
                height: '100vh',
                display: 'flex',
                flexDirection: 'column',
                backgroundColor: '#e5ddd5',
                position: 'relative',
            }}
        >
            <Box
                sx={{
                    padding: '10px 15px',
                    backgroundColor: '#ededed',
                    borderBottom: '1px solid #ccc',
                }}
            >
                <Typography variant="h6" sx={{fontWeight: 'bold'}}>
                    {'khaled Omar'}
                </Typography>
            </Box>
            <Box
                sx={{
                    flexGrow: 1,
                    padding: '20px',
                    overflowY: 'auto',
                }}
            >
                {messages.map((msg, index) => (
                    <Box
                        key={index}
                        sx={{
                            display: 'flex',
                            flexDirection: msg.sender === 'You' ? 'row-reverse' : 'row',
                            marginBottom: '15px',
                        }}
                    >
                        <Box
                            sx={{
                                backgroundColor: msg.sender === 'You' ? '#dcf8c6' : '#fff',
                                padding: '10px 15px',
                                borderRadius: '7.5px',
                                maxWidth: '70%',
                                boxShadow: '0 1px 2px rgba(0, 0, 0, 0.2)',
                            }}
                        >
                            <Typography variant="body1" sx={{wordWrap: 'break-word'}}>
                                {msg.text}
                            </Typography>
                            <Typography
                                variant="caption"
                                sx={{
                                    display: 'block',
                                    textAlign: msg.sender === 'You' ? 'right' : 'left',
                                    color: '#888',
                                    marginTop: '5px',
                                }}
                            >
                                {msg.sender}
                            </Typography>
                        </Box>
                    </Box>
                ))}
            </Box>
            <Divider/>
            <Box
                sx={{
                    display: 'flex',
                    padding: '10px 15px',
                    backgroundColor: '#f0f0f0',
                    position: 'sticky',
                    bottom: 0,
                }}
            >
                <TextField
                    fullWidth
                    variant="outlined"
                    placeholder="Type a message"
                    value={message}
                    onChange={(e) => setMessage(e.target.value)}
                    sx={{
                        backgroundColor: '#fff',
                        borderRadius: '20px',
                    }}
                />
                <Button
                    variant="contained"
                    color="primary"
                    onClick={handleSendMessage}
                    sx={{marginLeft: '10px', borderRadius: '10px'}}
                >
                    Send
                </Button>
            </Box>
        </Paper>
    )
}

export default ChatDetails
