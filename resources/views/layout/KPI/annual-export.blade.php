<form class="kpi-annual-export d-flex gap-2 mb-0" action="{{ route('kpi.export') }}" method="GET">
<input type="hidden" name="preview" value="1"><input type="hidden" name="report" value="{{ $reportKind }}"><input type="hidden" name="role_id" value="{{ $selectedRole->role_id }}"><input type="hidden" name="year" value="{{ $selectedYear }}"><input type="hidden" name="scope" value="all">
<button class="btn kpi-print-button" name="format" value="pdf">PDF</button><button class="btn kpi-print-button" name="format" value="xlsx">Excel</button>
</form>
