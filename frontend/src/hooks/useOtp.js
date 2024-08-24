import {useState} from "react";
import OtpService from "../services/OtpService.js";

export default function useOtp() {
    const [displayOtpForm, setDisplayOtpForm] = useState(false)
    const [otp, setOtp] = useState({})

    const sendOtp = async (email) => {
        const data = await OtpService.sendOtp(email)
        if (!data.ref) {
            return;
        }
        setOtp({...otp, ref: data.ref})
        setDisplayOtpForm(true)
    }
    return {displayOtpForm, setDisplayOtpForm, otp, setOtp, sendOtp}
}
