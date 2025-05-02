import {useEffect} from "react";
import {toast} from "react-toastify";

function useErrorNotification({
                                  isError,
                                  title,
                                  description,
                                  status = 'error'
                              }: { isError: boolean, title: string, description: string, status: string }) {
    // const toast = useToast();
    useEffect(() => {
        if (isError) {
            toast(title + " " + description + " " + status);
        }
    }, [isError]);
}

export default useErrorNotification;
