import { createApp } from './app'

export default async function (context) {
    const { app, router } = createApp();

    await router.push(context.url);

    const matchedComponents = router.currentRoute.value.matched;

    if (!matchedComponents.length) {
        throw { code: 404 };
    }

    return app;
}
