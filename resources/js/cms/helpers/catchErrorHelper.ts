import type { AxiosError } from "axios";
import type { MessageTypes } from "@/cms/types/message.d";

export const catchErrorHelper = (error: any) => {
    const axiosError = error as AxiosError<MessageTypes>;
    return axiosError.response?.data ?? {
        code: 'error_unknown',
        message: {
            head: 'Error',
            detail: [error.message]
        }
    } as MessageTypes
}