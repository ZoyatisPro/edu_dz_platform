import { usePage } from '@inertiajs/react';
import { useEffect, useRef } from 'react';

type TurnstileApi = {
    render: (
        container: HTMLElement,
        options: Record<string, unknown>,
    ) => string;
    remove: (widgetId: string) => void;
};

declare global {
    interface Window {
        turnstile?: TurnstileApi;
    }
}

const SCRIPT_ID = 'cf-turnstile-script';

/**
 * Cloudflare Turnstile widget.
 *
 * The widget is only mounted when a site key is configured, which keeps local
 * development and tests free of third-party scripts. Turnstile injects the
 * `cf-turnstile-response` field consumed by the registration action.
 */
export function TurnstileWidget() {
    const { turnstileSiteKey } = usePage().props;
    const container = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!turnstileSiteKey || !container.current) {
            return;
        }

        let widgetId: string | undefined;

        const render = (): void => {
            if (!window.turnstile || !container.current) {
                return;
            }

            widgetId = window.turnstile.render(container.current, {
                sitekey: turnstileSiteKey,
                theme: 'auto',
            });
        };

        if (window.turnstile) {
            render();

            return () => {
                if (widgetId) {
                    window.turnstile?.remove(widgetId);
                }
            };
        }

        let script = document.getElementById(
            SCRIPT_ID,
        ) as HTMLScriptElement | null;

        if (!script) {
            script = document.createElement('script');
            script.id = SCRIPT_ID;
            script.src =
                'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        }

        script.addEventListener('load', render);

        return () => {
            script?.removeEventListener('load', render);
        };
    }, [turnstileSiteKey]);

    if (!turnstileSiteKey) {
        return null;
    }

    return <div ref={container} data-sitekey={turnstileSiteKey} />;
}
