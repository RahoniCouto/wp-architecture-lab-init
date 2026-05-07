<?php
declare( strict_types=1);

namespace ArchitectureLab\PluginInicial\Admin;

use ArchitectureLab\PluginInicial\Infrastructure\OptionRepository;

final class SettingsPage {
    private const PAGE_SLUG = 'architecture-lab-psr-4-inicial';
    private const NONCE_ACTION = 'architecturelab_psr4_inicial_save_nonce';
    private const NONCE_NAME = 'architecturelab_psr4_inicial_nonce';

    public function __construct(
        private readonly OptionRepository $optionRepository
    ){

    }

    public function register():void{
        add_action('admin_menu', [$this, 'addPage']);
        add_action('admin_post_architecture_lab_psr4_save', [$this, 'handleSave']);
    }

    public function addPage():void{
        add_management_page(
            __('Architecture Lab', 'architecture-lab'),
            __('Architecture Lab', 'architecture-lab'),
            'manage_options',
            self::PAGE_SLUG,
            [$this,  'render']
        );
    }

    public function render():void{
        if( !current_user_can( 'manage_options' ) ){
            wp_die( esc_html__( 'Sem permissão de acesso', 'architecture-lab' ));
        }

        $message = $this->optionRepository->getMessage();
        $type = $this->optionRepository->getType();
        $dashboardOnly = $this->optionRepository->isDashOnly();

        ?>
            <div class="wrap">
                <h1><?php echo esc_html__('Architecture Lab - PSR-4 Inicial', 'architecture-lab'); ?></h1>

                <p>
                    <?php echo esc_html__('Esta página demonstra uma estrutura de plugin PSR-4 com injeção de dependência e encapsulamento da Options API.', 'architecture-lab'); ?>
                </p>

                <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true') : ?>
                    <div class="notice notice-success is-dismissible">
                        <p><?php echo esc_html__('Configuração Salva', 'architecture-lab'); ?></p>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="architecture_lab_psr4_save">

                    <?php wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME); ?>

                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="architecture_lab_message">
                                        <?php echo esc_html__('Mensagem', 'architecture-lab'); ?>
                                    </label>
                                </th>
                                <td>
                                    <input
                                        type="text"
                                        id="architecture_lab_message"
                                        name="architecture_lab_message"
                                        value="<?php echo esc_attr($message); ?>"
                                        class="regular-text"
                                    >
                                    <p class="description">
                                        <?php echo esc_html__('Esta mensagem é exibida como aviso do administrador. Deixe em branco para a mensagem padrão.', 'architecture-lab'); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="architecture_lab_notice_type">
                                        <?php echo esc_html__('Tipo de aviso:', 'architecture-lab'); ?>
                                    </label>
                                </th>
                                <td>
                                    <select id="architecture_lab_notice_type" name="type">
                                        <option value="success" <?php selected($type, 'success'); ?>>Sucesso</option>
                                        <option value="error" <?php selected($type, 'error'); ?>>Erro</option>
                                        <option value="warning" <?php selected($type, 'warning'); ?>>Aviso</option>
                                        <option value="info" <?php selected($type, 'info'); ?>>Info</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <?php echo esc_html__('Visibilidade', 'architecture-lab'); ?>
                                </th>
                                <td>
                                    <label for="architecture_lab_dashboard_only">
                                        <input
                                            type="checkbox"
                                            id="architecture_lab_dashboard_only"
                                            name="dash_only"
                                            value="1"
                                            <?php checked($dashboardOnly); ?>
                                        >
                                        <?php echo esc_html__('Mostrar apenas no Dashboard', 'architecture-lab'); ?>
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <?php submit_button(__('Salvar Configuração', 'architecture-lab')); ?>
                </form>
            </div>
        <?php
    }

    public function handleSave():void{
        if( !current_user_can( 'manage_options' ) ){
            wp_die( esc_html__( 'Você não tem permissão para salvar', 'architecture-lab' ));
        }

        check_admin_referer(self::NONCE_ACTION, self::NONCE_NAME);

        $data = [
            'message' => '',
            'type' => 'success',
            'dash_only' => false,
        ];

        if (isset($_POST['architecture_lab_message']) && is_string($_POST['architecture_lab_message'])) {
            $data['message'] = sanitize_text_field(wp_unslash($_POST['architecture_lab_message']));
        }

        if (isset($_POST['type']) && is_string($_POST['type'])) {
            $type = sanitize_text_field(wp_unslash($_POST['type']));
            $data['type'] = in_array($type, ['success', 'error', 'warning', 'info'], true) ? $type : 'success';
        }

        $data['dash_only'] = isset($_POST['dash_only']);

        $this->optionRepository->save($data);

        wp_safe_redirect(
            add_query_arg(
                [
                    'page' => self::PAGE_SLUG,
                    'settings-updated' => 'true',
                ],
                admin_url('tools.php')
            )
        );

        exit;
    }

}