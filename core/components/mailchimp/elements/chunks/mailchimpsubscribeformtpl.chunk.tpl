<h2>MailChimp formulier</h2>
[[+form.error]]
<p class="info">Velden gemarkeerd met een sterretje zijn verplicht.</p>
<form action="[[~[[*id]]]]" method="post" name="mailchimp" class="form [[+form.submit:notempty=`form-active`]]">
    <div class="form-element [[+form.error.sex:notempty=`error`]]">
		<label for="sex">Aanhef</label>
		<div class="form-element-container">
			<select name="sex" id="sex">
			    <option value="man" [[+form.sex:FormIsSelected=`man`]]>Man</option>
			    <option value="female" [[+form.sex:FormIsSelected=`female`]]>Vrouw</option>
			</select> [[+form.error.sex]]
		</div>
	</div>
	<div class="form-element [[+form.error.name:notempty=`error`]]">
		<label for="name">Uw naam</label>
		<div class="form-element-container">
			<input type="text" name="name" id="name" value="[[+form.name]]" /> [[+form.error.name]]
		</div>
	</div>
	<div class="form-element [[+form.error.lastname:notempty=`error`]]">
		<label for="lastname">Uw achternaam</label>
		<div class="form-element-container">
			<input type="text" name="lastname" id="lastname" value="[[+form.lastname]]" /> [[+form.error.lastname]]
		</div>
	</div>
	<div class="form-element [[+form.error.email:notempty=`error`]]">
		<label for="email">Uw e-mailadres</label>
		<div class="form-element-container">
			<input type="text" name="email" id="email" value="[[+form.email]]" /> [[+form.error.email]]
		</div>
	</div>
	<div class="form-element">
		<div class="form-element-container">
			<button type="submit" name="submit" title="Versturen">Versturen</button>
		</div>
	</div>
</form>