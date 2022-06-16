<?php
/**
 * Notice Action.
 * 
 * @package ULTP\Notice
 * @since v.1.0.0
 */
namespace ULTP;

defined('ABSPATH') || exit;

/**
 * Notice class.
 */
class Notice {
    /**
	 * Setup class.
	 *
	 * @since v.1.0.0
	 */
    public function __construct(){
		add_action('admin_notices', array($this, 'ultp_installation_notice_callback'));
		add_action('admin_init', array($this, 'set_dismiss_notice_callback'));
	}

    /**
	 * Promotional Dismiss Notice Option Data
     * 
     * @since v.2.0.1
	 * @param NULL
	 * @return NULL
	 */
	public function set_dismiss_notice_callback() {
		if (!isset($_GET['disable_postx_notice_v1'])) {
			return ;
        }
        if (sanitize_key($_GET['disable_postx_notice_v1']) == 'yes') {
            set_transient( 'ultp_get_pro_notice_v6', 'off', 2592000 ); // 30 days notice
        }
	}

    /**
	 * Dismiss Notice HTML Data
     * 
     * @since v.1.0.0
	 * @param NULL
	 * @return STRING
	 */
	public function ultp_installation_notice_callback() {
		if (get_transient('ultp_get_pro_notice_v6') != 'off') {
            if (!ultimate_post()->is_lc_active()) {
                if (!isset($_GET['disable_postx_notice_v1'])) {
                    $this->ultp_notice_css();
                    ?>
                    <div class="wc-install ultp-pro-notice">
                        <!-- <div class="wc-install-body ultp-image-banner">
                            <a class="wc-dismiss-notice" href="<?php //echo esc_url( add_query_arg( 'disable_postx_notice_v1', 'yes' ) ); ?>"><span class="dashicons dashicons-dismiss"></span></a>
                            <a class="ultp-btn-image" target="_blank" href="<?php //echo esc_url(ultimate_post()->get_premium_link()); ?>">
                                <img loading="lazy" src="<?php //echo esc_url(ULTP_URL.'assets/img/banner.jpg'); ?>" alt="up to 40% Off" />
                            </a>
                        </div> -->
                        <!-- <div class="wc-install-body">
                            <div><?php //wp_kses(__('<strong>PostX - ⚡Flash Sale⚡</strong> is LIVE! Spend <strong style="color:#1cb53e;">25% LESS</strong> on Premium Features for a <strong>⏳LIMITED Time!</strong>', 'ultimate-post'), 'post'); ?></div>
                            <a class="button button-primary button-hero ultp-btn-notice-pro" target="_blank" href="<?php //echo esc_url(ultimate_post()->get_premium_link()); ?>"><span class="dashicons dashicons-image-rotate"></span><?php //_e('Get Now', 'ultimate-post'); ?></a>
                            <a class="button-secondary button-large" href="<?php //echo esc_url( add_query_arg( 'disable_postx_notice_v1', 'yes' ) ); ?>"><?php //_e('No Thanks!', 'ultimate-post'); ?></a>
                        </div> -->
                        <div class="wc-install-body">
                            <div><?php wp_kses(__("Be a part of <strong>PostX's</strong> 🍉 <strong style='color:#1cb53e;'>Summer Sale</strong> Celebration by Getting Up to 🔥 <strong>30% DISCOUNT</strong> on PostX! ", 'ultimate-post'), 'post'); ?></div>
                           <!--  <div><?php //wp_kses(__('Thanks for using the free version of <strong>PostX Gutenberg Blocks</strong>. We have a special <strong>20% discount</strong> for a limited time. Use this coupon:', 'ultimate-post'), 'post'); ?><strong><code>POSTXAD20</code></strong></div> -->
                            <a class="button button-primary button-hero ultp-btn-notice-pro" target="_blank" href="<?php echo esc_url(ultimate_post()->get_premium_link()); ?>"><span class="dashicons dashicons-image-rotate"></span><?php esc_html_e('Upgrading to Pro', 'ultimate-post'); ?></a>
                            <a class="button-secondary button-large" href="<?php echo esc_url( add_query_arg( 'disable_postx_notice_v1', 'yes' ) ); ?>"><?php esc_html_e('No Thanks / Close.', 'ultimate-post'); ?></a>
                        </div>
                    </div>
                    <?php
                }
            }
		}
	}

    /**
	 * Admin Notice CSS File
     * 
     * @since v.1.0.0
	 * @param NULL
	 * @return STRING
	 */
	public function ultp_notice_css() {
		?>
		<style type="text/css">
            .wc-install {
                display: flex;
                align-items: center;
                background: #fff;
                margin-top: 40px;
                width: calc(100% - 50px);
                border: 1px solid #ccd0d4;
                padding: 12px 15px;
                border-radius: 4px;
                border-left: 3px solid #2271b1;
            }   
            .wc-install img {
                margin-right: 0; 
                max-width: 100%;
            }
            .wc-install-body {
                -ms-flex: 1;
                flex: 1;
                position: relative;
            }
            .wc-install-body h3 {
                margin-top: 0;
                font-size: 24px;
                margin-bottom: 15px;
            }
            .ultp-install-btn {
                margin-top: 15px;
                display: inline-block;
            }
			.wc-install .dashicons{
				display: none;
				animation: dashicons-spin 1s infinite;
				animation-timing-function: linear;
			}
			.wc-install.loading .dashicons {
				display: inline-block;
				margin-top: 12px;
				margin-right: 5px;
			}
            .ultp-pro-notice .wc-install-body h3 {
                font-size: 20px;
                margin-bottom: 5px;
            }
            .ultp-pro-notice .wc-install-body > div {
                max-width: 100%;
                margin-bottom: 10px;
            }
            .ultp-pro-notice .button-hero {
                padding: 8px 14px !important;
                min-height: inherit !important;
                line-height: 1 !important;
                box-shadow: none;
                border: none;
                transition: 400ms;
            }
            .ultp-pro-notice .ultp-btn-notice-pro {
                background: #2271b1;
                color: #fff;
            }
            .ultp-pro-notice .ultp-btn-notice-pro:hover,
            .ultp-pro-notice .ultp-btn-notice-pro:focus {
                background: #185a8f;
            }
            .ultp-pro-notice .button-hero:hover,
            .ultp-pro-notice .button-hero:focus {
                border: none;
                box-shadow: none;
            }
			@keyframes dashicons-spin {
				0% {
					transform: rotate( 0deg );
				}
				100% {
					transform: rotate( 360deg );
				}
			}
			.wc-dismiss-notice {
                position: absolute;
                text-decoration: none;
                float: right;
                right: 0px;
                color: #787c82;
                transition: 400ms;
                top: 0px
            }
            .wc-dismiss-notice:hover {
                color:red;
            }
			.wc-dismiss-notice .dashicons{
                display: inline-block;
                text-decoration: none;
                animation: none;
                font-size: 16px;
			}
		</style>
		<?php
    }

}