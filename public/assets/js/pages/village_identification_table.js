$(document).ready(function() {
	$('#village-identification-table').DataTable({
		processing: true,
		serverSide: true,
		scrollX: true,
		ajax: identified_village_model_route,

		dom: 'Bfrtip',
		"autoWidth": false,

		buttons: [{
				extend: 'excel',
				exportOptions: {
					columns: [0, ':visible'],
					// columns: 'th:not(:last-child)'
				}
			},
			{
				extend: 'colvis',
				postfixButtons: ['colvisRestore'],
				columnText: function(dt, idx, title) {
					return (idx + 1) + ': ' + title;
				},
				collectionLayout: 'fixed columns',
				collectionTitle: 'Column visibility control',
			}
		],
		columnDefs: [{
			targets: [2, 3, 8, 9, 10,13,14,15,16],
			visible: false
		}],

		columns: [{
				data: 'DT_RowIndex',
				name: 'DT_RowIndex'
			},
			{
				data: 'district.name',
				name: 'district.name',
			},
			{
				data: 'taluka',
				name: 'taluka',
			},
			{
				data: 'uc',
				name: 'uc',
			},
			{
				data: 'deh_id',
				name: 'deh_id',
			},
			{
				data: 'village_id',
				name: 'village_id',
			},
			{
				data: 'total_population',
				name: 'total_population',
			},
			{
				data: 'total_houses',
				name: 'total_houses',
			},
			{
				data: 'percent_crop_damaged',
				name: 'percent_crop_damaged',
			},
			{
				data: 'percent_livestock_damaged',
				name: 'percent_livestock_damaged',
			},
			{
				data: 'percent_infrastructure_damaged',
				name: 'percent_infrastructure_damaged',
			},
			{
				data: 'percent_village_damaged',
				name: 'percent_village_damaged',
			},
			{
				data: 'cfw_beneficiaries',
				name: 'cfw_beneficiaries',
			},
			{
				data: 'latitude',
				name: 'latitude',
			},
			{
				data: 'longitude',
				name: 'longitude',
			},
			{
				data: 'bbcm_date',
				name: 'bbcm_date',
			},
			{
				data: 'participants_in_bbcm',
				name: 'participants_in_bbcm',
			},
			{
				data: 'status',
				name: 'status',
			},
			{
				data: 'action',
				name: 'action',
				orderable: false,
				searchable: false
			},
		]
	});
});