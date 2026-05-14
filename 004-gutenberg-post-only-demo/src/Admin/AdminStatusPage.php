<?php
declare(strict_types=1);

namespace ArchitectureLab\GutenbergPostOnlyDemo\Admin;

use ArchitectureLab\GutenbergPostOnlyDemo\Services\EditorPolicyService;

final class AdminStatusPage {
    private const PAGE_SLUG = 'gutenberg-policy-demo';

    public function __construct(
        private readonly EditorPolicyService $policyService
    ){}

    public function register(): void {
        add_action('admin_menu', [$this, 'addPage']);
    }

    public function addPage(): void {
        add_management_page(
            __('Gutenberg Policy Demo', 'architecture-lab'),
            __('Gutenberg Policy Demo', 'architecture-lab'),
            'manage_options',
            self::PAGE_SLUG,
            [$this, 'render']
        );
    }

    public function render(): void {
        if(!current_user_can( 'manage_options' )) {
            wp_die(esc_html( 'Você não tem permissão para acessar esta página.', 'architecture-lab' ));
        }

        $postTypes = [
            'post' => __('Posts', 'architecture-lab'),
            'page' => __('Pages', 'architecture-lab'),
        ];

        ?>
            <div class="wrap">
                <h1>
                    <?php echo esc_html( 'Gutenberg Control Painel', 'architecture-lab' ) ?>
                </h1>
                <p>
                    <?php echo esc_html( 'Página mostra quais post_types podem usar o editor Gutenberg', 'architecture-lab' ) ?>
                </p>

                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th><?php echo esc_html( 'Post Type', 'architecture-lab' ) ?></th>
                            <th><?php echo esc_html( 'Editor Gutenberg', 'architecture-lab' ) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($postTypes as $postType => $label):
                                $enabled = $this->policyService->canUseBlockEditor($postType);
                                ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo esc_html($label) ?></strong>
                                            <code><?php echo esc_html($postType) ?></code>
                                        </td>
                                        <td>
                                            <?php
                                                if($enabled):
                                                    ?>
                                                        <span style="color: #008a20; font-weight: 600;">
                                                            <?php echo esc_html__('Ativo', 'architecture-lab'); ?>
                                                        </span>
                                                    <?php
                                                else:
                                                    ?>
                                                        <span style="color: #b32d2e; font-weight: 600;">
                                                            <?php echo esc_html__('Inativo', 'architecture-lab'); ?>
                                                        </span>
                                                    <?php
                                                endif;
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                            endforeach;
                        ?>
                    </tbody>
                </table>

                <p>
                    <?php echo esc_html( 'Regra atual: apenas "post" podem usar o Gutenberg', 'architecture-lab' ) ?>
                </p>
            </div>
        <?php
    }
}