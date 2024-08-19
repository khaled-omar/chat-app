import {red} from '@mui/material/colors';
import {createTheme} from '@mui/material/styles';

const theme = createTheme({
    palette: {
        primary: {
            main: '#19857b',
        },
        secondary: {
            main: '#556cd6',
        },
        error: {
            main: red.A400,
        },
    },
});

export default theme;
