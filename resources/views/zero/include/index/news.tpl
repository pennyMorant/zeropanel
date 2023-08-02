<!--
<div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{ default:'300px', 'md': '500px' }" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close">
	<div class="card w-100 rounded-0 border-0" id="kt_drawer_chat_messenger">
		<div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
			<div class="card-title">
				<div class="me-3">
					<p class="fs-4 fw-bold text-gray-900 me-1 mb-2 lh-1">{$trans->t('news')}</a>
				</div>
			</div>
			<div class="card-toolbar">
				<div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_chat_close">
					<span class="svg-icon svg-icon-2">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
							<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
						</svg>
					</span>
				</div>
			</div>
		</div>
		<div class="card-body" id="kt_drawer_chat_messenger_body">
			
			<div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header" data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">			
				{foreach $anns as $ann}
					<div class="card card-bordered mb-2">
						<div class="card-body">
							<p class="fs-5 fw-semibold text-gray-800">{$ann->content}</p>
							<p class="fs-6 text-muted">{date('Y-m-d', $ann->updated_at)}</p>
						</div>
					</div>
				{/foreach}
			</div>	
		</div>
	</div>
</div>
-->