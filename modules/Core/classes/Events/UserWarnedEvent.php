<?php

class UserWarnedEvent extends AbstractEvent implements DiscordDispatchable {

    public User $punished;
    public User $punisher;
    public string $reason;

    public function __construct(User $punished, User $punisher, string $reason) {
        $this->punished = $punished;
        $this->punisher = $punisher;
        $this->reason = $reason;
    }

    public static function description(): string {
        return (new Language())->get('admin', 'warning_hook_info');
    }

    public function toDiscordWebook(): DiscordWebhookBuilder {
        $language = new Language('core', DEFAULT_LANGUAGE);

        return DiscordWebhookBuilder::make()
            ->setUsername($this->punished->getDisplayname() . ' | ' . SITE_NAME)
            ->setAvatarUrl($this->punished->getAvatar(128, true))
            ->addEmbed(function (DiscordEmbed $embed) use ($language) {
                return $embed
                    ->setDescription($language->get('admin', 'user_warned_webhook', [
                        'punished' => $this->punished->getDisplayname(),
                        'punisher' => $this->punisher->getDisplayname(),
                    ]))
                    ->addField($language->get('admin', 'reason'), $this->reason);
            });
    }
}
