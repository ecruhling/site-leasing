<?php

// vars

?>
<div class="wrap site-leasing-settings-wrap">

    <h1><?php _e( 'Updates', 'site-leasing' ); ?></h1>

    <div class="site-leasing-box" id="site-leasing-license-information">
        <div class="title">
            <h3><?php _e( 'License Information', 'site-leasing' ); ?></h3>
        </div>
        <div class="inner">
            <p><?php printf( __( 'To unlock updates, please enter your license key below. If you don\'t have a licence key, please see <a href="%s" target="_blank">details & pricing</a>.', 'site-leasing' ), esc_url( 'https://www.advancedcustomfields.com/pro' ) ); ?></p>
            <form action="" method="post">
                <div class="site-leasing-hidden">
                </div>
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th>
                            <label for="site-leasing-field-site_leasing_licence"><?php _e( 'License Key', 'site-leasing' ); ?></label>
                        </th>
                        <td>
							<?php

							// render field
							site_leasing_render_field( array(
								'type'     => 'text',
								'name'     => 'site_leasing_license',
								'value'    => str_repeat( '*', strlen( 1 ) ),
								'readonly' => false
							) );

							?>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <input type="submit" value="<?php echo 'button'; ?>" class="button button-primary">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>

        </div>

    </div>

    <div class="site-leasing-box" id="site-leasing-update-information">
        <div class="title">
            <h3><?php _e( 'Update Information', 'site-leasing' ); ?></h3>
        </div>
        <div class="inner">
            <table class="form-table">
                <tbody>
                <tr>
                    <th>
                        <label><?php _e( 'Current Version', 'site-leasing' ); ?></label>
                    </th>
                    <td>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e( 'Latest Version', 'site-leasing' ); ?></label>
                    </th>
                    <td>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e( 'Update Available', 'site-leasing' ); ?></label>
                    </th>
                    <td>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style type="text/css">
    #site_leasing_licence {
        width: 75%;
    }

    #site-leasing-update-information td h4 {
        display: none;
    }
</style>
