export interface MessageTypes<Data = any> {
    code: string,
    message : {
        head: string,
        detail: array,
    },
    data: Data
}