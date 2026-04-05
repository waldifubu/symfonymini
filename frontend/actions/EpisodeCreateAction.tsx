import axios, {AxiosError} from 'axios';

export interface EpisodeActionState {
    success: boolean;
    message: string;
}

// Bind `seriesId` before passing to useActionState:
//   useActionState(handleSubmit.bind(null, id), null)
export async function handleSubmit(
    seriesId: string | undefined,
    prevState: EpisodeActionState | null,
    formData: FormData
): Promise<EpisodeActionState> {
    if (!seriesId) {
        return { success: false, message: 'Missing series ID.' };
    }

    const fields = [
        'episode', 'title', 'description', 'content',
        'pubDate', 'episodeType', 'author', 'tags',
        'coverUrl', 'fileUrl', 'fileLength', 'duration',
        'coverId', 'radioplayId',
    ] as const;

    const body = new FormData();
    for (const field of fields) {
        const value = formData.get(field);
        if (value !== null) body.append(field, value);
    }

    try {
        console.log('Submitting episode with data:', Object.fromEntries(body.entries()));
        await axios.post(`/api/series/${seriesId}/episodes/`, body);
        return { success: true, message: 'Episode created successfully!' };
    } catch (e) {
        const error = e as AxiosError<{ message?: string }>;
        const msg = error.response?.data?.message ?? error.message ?? 'Unknown error.';
        return { success: false, message: msg };
    }
}
