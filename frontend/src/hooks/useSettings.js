import SettingService from "../services/SettingService.js";
import {useQuery} from "@tanstack/react-query";

export default function useSettings(keys) {

    const {isPending, error, data: settings} = useQuery({
        queryKey: ['settings'],
        queryFn: () => SettingService.get(keys)
    })

    return {isPending, error, settings}
}
