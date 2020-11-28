<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<table class="wp-list-table widefat fixed striped table-view-list">
		<thead>
			<tr>
				<th><?php esc_html_e("Status", 'ssgp'); ?></th>
				<th><?php esc_html_e("Name", 'ssgp'); ?></th>
				<th><?php esc_html_e("Description", 'ssgp'); ?></th>
				<th><?php esc_html_e("Action", 'ssgp'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td></td>
				<td><?php esc_html_e("Permalink Layout", 'ssgp'); ?></td>
				<td><?php esc_html_e(
						"Change your permalink settings to a format, that does not use query parameters,
						as those are not supported on static pages.",
						'ssgp');
					?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td><?php esc_html_e("Comments", 'ssgp'); ?></td>
				<td><?php esc_html_e(
						"Disable or replace comment forms, otherwise they will not work on a generated static site.",
						'ssgp');
					?></td>
				<td></td>
			</tr>
		</tbody>
		<tfoot>
		<tr>
			<th><?php esc_html_e("Status", 'ssgp'); ?></th>
			<th><?php esc_html_e("Name", 'ssgp'); ?></th>
			<th><?php esc_html_e("Description", 'ssgp'); ?></th>
			<th><?php esc_html_e("Action", 'ssgp'); ?></th>
		</tr>
		</tfoot>
	</table>
</div>