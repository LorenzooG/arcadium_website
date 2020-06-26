import React, { useEffect, useState } from 'react'

import { toast } from 'react-toastify'

import { requestServerIcon, requestServerInfo, toastMessage } from '~/utils'

import { ErrorComponent } from '~/components'

import { app, errors, locale } from '~/services'

import {
  Button,
  ButtonWrapper,
  Color,
  Container,
  Field,
  Wrapper
} from './styles'

const Sidebar: React.FC = () => {
  const [error, setError] = useState(false)
  const [players, setPlayers] = useState(0)
  const [max, setMax] = useState(0)
  const [loading, setLoading] = useState(true)

  const address = app.serverAddress()

  useEffect(() => {
    if (loading) {
      requestServerInfo(address)
        .then(res => {
          setMax(res.MaxPlayers)
          setPlayers(res.PlayersOnline)

          if (!res.Online) {
            throw new Error()
          }
        })
        .catch(() => {
          setError(true)

          errors.handle(locale.getTranslation('error.fetch'))
        })
    }
    setLoading(false)
  }, [address, error, loading])

  async function handleCopyIp() {
    try {
      const localeAction = locale.getTranslation('action.copy.ip')

      await navigator.clipboard.writeText(address)

      const localeSuccessNotification = locale.getTranslation(
        'notification.success'
      )

      toast.success(
        toastMessage(localeSuccessNotification.replace('$action', localeAction))
      )
    } catch (exception) {
      errors.handleForException(exception)
    }
  }

  return (
    <Wrapper>
      <Container>
        <Color>
          {error ? (
            <ErrorComponent error={locale.getTranslation('error.fetch')} />
          ) : (
            <>
              <img src={requestServerIcon(address)} alt={address} />

              <Field>
                <strong>
                  {locale
                    .getTranslation('message.server.ip')
                    .replace('$ip', address)}
                </strong>
              </Field>
              <Field>
                {locale.getTranslation('message.come.play.with.us')}
              </Field>
              <h5>
                {locale
                  .getTranslation('message.players.online')
                  .replace('$online', players?.toString())
                  .replace('$max', max?.toString())}
              </h5>
            </>
          )}
        </Color>

        <ButtonWrapper>
          <Button onClick={handleCopyIp}>
            {locale.getTranslation('action.copy.ip').toUpperCase()}
          </Button>
        </ButtonWrapper>
      </Container>

      <iframe
        src={`https://discordapp.com/widget?id=${app.discordServerId()}&theme=dark`}
      />
    </Wrapper>
  )
}

export default Sidebar
