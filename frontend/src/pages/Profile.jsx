import {BottomNavigation, BottomNavigationAction, Box, Typography} from '@mui/material'
import RestoreIcon from '@mui/icons-material/Restore';
import FavoriteIcon from '@mui/icons-material/Favorite';
import LocationOnIcon from '@mui/icons-material/LocationOn';

import * as React from 'react';


function Profile() {
    return (

        <SimpleBottomNavigation sx={{}}/>
    )
}

export default Profile

function SimpleBottomNavigation() {
    const [value, setValue] = React.useState(0);

    return (
        <Box sx={{width: 500}}>
            <BottomNavigation
                showLabels
                value={value}
                onChange={(event, newValue) => {
                    setValue(newValue);
                }}
            >
                <BottomNavigationAction label="Recents" icon={<RestoreIcon/>}/>
                <BottomNavigationAction label="Favorites" icon={<FavoriteIcon/>}/>
                <BottomNavigationAction label="Nearby" icon={<LocationOnIcon/>}/>
            </BottomNavigation>
        </Box>
    );
}