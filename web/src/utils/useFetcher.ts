import { useCallback, useEffect, useState } from 'react'

interface Settings<F, D> {
  fetcher: F
  initialData: D
}

interface ReturnData<D> {
  data: D | null
  error: Error | null
  loading: boolean
}

export function useFetcher<D, F extends () => Promise<D>>({
  fetcher: _fetcher,
  initialData,
}: Settings<F, D>): ReturnData<D> {
  const [loading, setLoading] = useState(true)
  const [data, setData] = useState<D | null>(null)
  const [error, setError] = useState<Error | null>(null)

  const fetcher = useCallback(_fetcher, [])

  useEffect(() => {
    async function fetchData() {
      if (initialData) return

      try {
        setData(await fetcher())
      } catch (error) {
        setError(error)
      }
    }

    fetchData().then(() => setLoading(false))
  }, [fetcher, initialData])

  if (initialData) {
    return {
      data: initialData,
      loading: false,
      error: null,
    }
  }

  return { data, loading, error }
}
