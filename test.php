<form id="form1" name="form1" action="report_sales_summery.php" target="_blank" method="get">
	<div class="row">
		<div class="col-md-3">
			<label> Customer Wise</label>
			<input type="checkbox" name="chkcus" id="chkcus">
		</div>
		<div class="col-md-3">
			<label>Customer Code</label>
			<input type="text" name="cuscode" id="cuscode" class="text_purchase3">
		</div>
		<div class="col-md-3">
			<a href="" onclick="NewWindow('serach_customer.php','mywin','800','700','yes','center');return false"
				onfocus="this.blur()">
				<input type="text" name="cusname" id="cusname" class="text_purchase3">
			</a>
			<input type="text" class="text_purchase3" disabled="disabled" id="cus_address" name="cus_address">

			|
			<a href="serach_customer.php?stname=rep_outstand_state"
				onclick="NewWindow(this.href,'mywin','800','700','yes','center');return false" onfocus="this.blur()">
				<input type="button" name="searchcust" id="searchcust" value="..." class="btn_purchase1">
			</a>
		</div>

		<div class="row mb-3">
			<div class="col-md-3">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="radio" id="optsales" value="optsales" checked
						onclick="chk_sales();">
					<label class="form-check-label" for="optsales">Sales</label>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="radio" id="optreturn" value="optreturn"
						onclick="chk_return();">
					<label class="form-check-label" for="optreturn">Return</label>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="radio" id="optsummery" value="optsummery"
						onclick="chk_summery();">
					<label class="form-check-label" for="optsummery">Summary</label>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="radio" id="optscrap" value="optscrap">
					<label class="form-check-label" for="optscrap">Scrap</label>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-3">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="radio" id="optreceipt" value="optreceipt"
						onclick="chk_rec();">
					<label class="form-check-label" for="optreceipt">Receipt</label>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="radio" id="optitem" value="optitem">
					<label class="form-check-label" for="optitem">Item Sales</label>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="radio" id="opttraget" value="opttraget">
					<label class="form-check-label" for="opttraget">Target</label>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="radio" id="optgrninv" value="optgrninv">
					<label class="form-check-label" for="optgrninv">GRN With Inv No</label>
				</div>
			</div>
		</div>

	</div>

	<table width="928" border="0">
		<tbody>
			<tr>
				<td colspan="2" rowspan="2" align="left">
					<table width="500" border="0">
						<tbody>
							<tr>
								<th width="403" scope="col">
									<table width="400" border="0">
										<tbody>
											<tr>
												<th scope="col"><input type="radio" name="radio" id="optsales"
														value="optsales" checked="checked" onclick="chk_sales();"> Sales
												</th>
												<th scope="col"><input type="radio" name="radio" id="optreturn"
														value="optreturn" onclick="chk_return();"> Return</th>
												<th scope="col"><input type="radio" name="radio" id="optsummery"
														value="optsummery" onclick="chk_summery();"> Summery</th>
												<th scope="col"><input type="radio" name="radio" id="optscrap"
														value="optscrap"> Screap</th>
											</tr>
											<tr>
												<td><input type="radio" name="radio" id="radio" value="optreceipt"
														onclick="chk_rec();"> Reciept</td>
												<td><input type="radio" name="radio" id="optitem" value="optitem"> Item
													Sales</td>
												<td><input type="radio" name="radio" id="OPttraget" value="OPttraget">
													Target</td>
												<td><input type="radio" name="radio" id="optgrninv" value="optgrninv">
													GRN With Inv No</td>
											</tr>
										</tbody>
									</table>
								</th>
								<th width="87" scope="col">
									<table width="300" border="0">
										<tbody>
											<tr>
												<th scope="col">
													<div id="type">
														<select name="cmbtype" id="cmbtype" class="text_purchase3">
															<option value="All">All</option>
															<option value="GRN">GRN</option>
															<option value="DGRN">DGRN</option>
															<option value="Credit Note">Credit Note</option>
														</select>
													</div>
												</th>
												<th scope="col">
													<div id="dev">
														<select name="cmbdev" id="cmbdev" class="text_purchase3">
															<option value="Computer">Office Sale</option>
														</select>
													</div>
												</th>
											</tr>
											<tr>
												<td>
													<div id="summery">
														<select name="cmbsummerytype" id="cmbsummerytype"
															class="text_purchase3">
															<option value="All">All</option>
															<option value="Seperate">Seperate</option>
															<option value="Deffective">Deffective</option>
														</select>
													</div>
												</td>
												<td>
													<div id="cashdis"><input type="checkbox" name="chk_cash"
															id="chk_cash">Cash Dis</div>
													<div id="svat"><input type="checkbox" name="chk_svat"
															id="chk_svat">SVAT</div>
												</td>
											</tr>
										</tbody>
									</table>
								</th>
							</tr>
						</tbody>
					</table>
				</td>
				<td width="128" rowspan="2" align="left">
					<table width="100" border="0">
						<tbody>
							<tr>
								<th scope="col">
									<div id="rectype">
										<select name="cmbRECtype" id="cmbRECtype" class="text_purchase3">
											<option value="All">All</option>
											<option value="Normal">Normal</option>
											<option value="Ret.ch">Ret.ch</option>
										</select>
									</div>
								</th>
							</tr>
							<tr>
								<td>
									<div id="chkrettype">
										<select name="cmbretchktype" id="cmbretchktype" class="text_purchase3">
											<option value="All">All</option>
											<option value="Cash">Cash</option>
											<option value="J/Entry">J/Entry</option>
											<option value="R/Deposit">R/Deposit</option>
											<option value="C/TT">C/TT</option>
										</select>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td><input type="checkbox" name="chk_stamp" id="chk_stamp"> Stamp Duty</td>
				<td><input type="checkbox" name="chk_stamp1" id="chk_stamp1"> Summery</td>
				<td width="76" align="left"><input type="checkbox" name="chk_discount" id="chk_discount"> Discount</td>
			</tr>
			<tr>
				<td align="left"><input type="text" name="txt_disper" id="txt_disper" class="text_purchase3"></td>
			</tr>
			<tr>
				<td colspan="4" align="left">
					<fieldset>
						<table width="821" border="0">
							<tbody>
								<tr>
									<th width="147" scope="col"><input type="radio" name="radio2" id="optdaily"
											value="optdaily" checked="checked"> Daily</th>
									<th width="59" scope="col">&nbsp;</th>
									<th width="84" scope="col">&nbsp;</th>
									<th width="144" scope="col">&nbsp;</th>
									<th width="162" scope="col">
										<input type="text" class="label_purchase" value="Marketing Executive"
											disabled="disabled">
									</th>
									<th colspan="2" scope="col">
										<select name="cmbrep" id="cmbrep" onkeypress="keyset('dte_dor',event);"
											onchange="custno('cash_rec_rep');" class="text_purchase3">
											<option value="All">All</option>
											<option value="1">1 Office Sales</option>
											<option value="32">32 Chaminda Nawarathna</option>
											<option value="47">47 Wasantha Kodithuwakku</option>
											<option value="57">57 C. PUSHPAKUMARA</option>
											<option value="63">63 Ravindra Weerasekara</option>
											<option value="73">73 Chaminda Nawarathna (Tender)</option>
											<option value="90">90 Thusitha Kumara</option>
											<option value="91">91 Ruwan Gamage</option>
											<option value="92">92 Uditha Anuradha</option>
											<option value="95">95 Dulan Devnaka</option>
											<option value="96">96 Dennis Priyanga</option>
											<option value="97">97 Wasantha Kodithuwakku</option>
											<option value="100">100 K.G.G. Jayanath</option>
											<option value="101">101 Lasantha Bandara</option>
											<option value="102">102 Dilshan Malindu</option>
											<option value="103">103 Malaka Godakumbura</option>
											<option value="104">104 Kushan Thennakoone</option>
											<option value="105">105 Prasad Nilupul</option>
											<option value="107">107 Lalinda Saranga</option>
											<option value="108">108 Lahiru Shanaka</option>
											<option value="110">110 Manoj Priyantha</option>
											<option value="111">111 Janahitha Kumara</option>
											<option value="112">112 Darshana Priyankara</option>
											<option value="113">113 Dilshan Malindu (L)</option>
											<option value="114">114 Shiron Rangana</option>
											<option value="115">115 Jagath Deshapriya</option>
											<option value="116">116 Manjula Sampath</option>
											<option value="117">117 Aminda Udayanga</option>
											<option value="118">118 Janith Perera</option>
											<option value="119">119 Mevan Thanuja</option>
											<option value="120">120 Prabuddha Tharupathi</option>
											<option value="121">121 Wasantha Rathnayake</option>
											<option value="122">122 Rajitha Subashana</option>
											<option value="123">123 Northern Sale</option>
										</select>
									</th>
								</tr>
								<tr>
									<td><input type="radio" name="radio2" id="optperiod" value="optperiod"> For Given
										Period</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>
										<input type="text" class="label_purchase" value="Brand" disabled="disabled">
									</td>
									<td colspan="2">
										<select name="CmbBrand" id="CmbBrand" onkeypress="keyset('searchitem',event);"
											class="text_purchase3" onchange="setord();">
											<option value="All">All</option>
											<option value="AGATE">AGATE</option>
											<option value="ALCEED">ALCEED</option>
											<option value="AONAITE">AONAITE</option>
											<option value="ATLANDER">ATLANDER</option>
											<option value="ATLASBX">ATLASBX</option>
											<option value="BATTERY TESTER">BATTERY TESTER</option>
											<option value="BRAVIA">BRAVIA</option>
											<option value="CANTOP">CANTOP</option>
											<option value="CHENGSHAN">CHENGSHAN</option>
											<option value="COMFORSER">COMFORSER</option>
											<option value="COMPASAL">COMPASAL</option>
											<option value="COMPASAL TBR">COMPASAL TBR</option>
											<option value="CONDOR">CONDOR</option>
											<option value="CONSTANCY">CONSTANCY</option>
											<option value="CONTINENTAL">CONTINENTAL</option>
											<option value="COOPER">COOPER</option>
											<option value="DELTA PACE">DELTA PACE</option>
											<option value="DOUBLE ROAD">DOUBLE ROAD</option>
											<option value="DUNLOP">DUNLOP</option>
											<option value="ETERNITY">ETERNITY</option>
											<option value="EXOCELL">EXOCELL</option>
											<option value="FESITE">FESITE</option>
											<option value="GENEX">GENEX</option>
											<option value="GLOBAL RACE">GLOBAL RACE</option>
											<option value="GLOBATT ACE">GLOBATT ACE</option>
											<option value="GLOBATT PACE">GLOBATT PACE</option>
											<option value="GLOBATT RACE">GLOBATT RACE</option>
											<option value="GOALSTAR">GOALSTAR</option>
											<option value="GREENTOUR">GREENTOUR</option>
											<option value="HHO">HHO</option>
											<option value="HOPSON">HOPSON</option>
											<option value="HYUNDAI">HYUNDAI</option>
											<option value="KAPSEN">KAPSEN</option>
											<option value="KONTROL">KONTROL</option>
											<option value="LANDE">LANDE</option>
											<option value="LANDSAIL">LANDSAIL</option>
											<option value="LINGLONG">LINGLONG</option>
											<option value="LINGLONG TL">LINGLONG TL</option>
											<option value="LINGLONG/N">LINGLONG/N</option>
											<option value="LIONSTONE">LIONSTONE</option>
											<option value="LOCAL BATTERY">LOCAL BATTERY</option>
											<option value="LOCAL TYRE">LOCAL TYRE</option>
											<option value="LONGLIFE">LONGLIFE</option>
											<option value="LUHE">LUHE</option>
											<option value="MAXXIS MC">MAXXIS MC</option>
											<option value="MINERVA">MINERVA</option>
											<option value="MIRAGE">MIRAGE</option>
											<option value="MIRAGE TBR">MIRAGE TBR</option>
											<option value="MRL">MRL</option>
											<option value="OHNICE">OHNICE</option>
											<option value="OKAYA">OKAYA</option>
											<option value="ORNET">ORNET</option>
											<option value="OTANI">OTANI</option>
											<option value="OTANI TBR">OTANI TBR</option>
											<option value="PLATIN">PLATIN</option>
											<option value="POWERTRAC">POWERTRAC</option>
											<option value="POWERTRAC TBR">POWERTRAC TBR</option>
											<option value="PRESA">PRESA</option>
											<option value="PYTHON">PYTHON</option>
											<option value="RAPID">RAPID</option>
											<option value="ROADMARCH">ROADMARCH</option>
											<option value="ROADSHINE">ROADSHINE</option>
											<option value="ROADSTONE">ROADSTONE</option>
											<option value="ROADSTONE CHINA">ROADSTONE CHINA</option>
											<option value="ROADSTONE TUBE">ROADSTONE TUBE</option>
											<option value="RYDANZ">RYDANZ</option>
											<option value="SONIX">SONIX</option>
											<option value="SONIX TBR">SONIX TBR</option>
											<option value="STUCCO HOPPER">STUCCO HOPPER</option>
											<option value="SUNFUL">SUNFUL</option>
											<option value="SUNFUL TBR">SUNFUL TBR</option>
											<option value="TAITONG">TAITONG</option>
											<option value="TEXXAN">TEXXAN</option>
											<option value="THREE-A">THREE-A</option>
											<option value="TRANSTONE">TRANSTONE</option>
											<option value="TUBE MASTER">TUBE MASTER</option>
											<option value="ULTRA MILE">ULTRA MILE</option>
											<option value="VAYU">VAYU</option>
											<option value="VEEDOL">VEEDOL</option>
											<option value="VOLTA">VOLTA</option>
											<option value="WIDEWAY">WIDEWAY</option>
											<option value="WILLFLY">WILLFLY</option>
											<option value="ZEETEX">ZEETEX</option>
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="text" class="label_purchase" value="Date" disabled="disabled"></td>
									<td colspan="2">
										<input type="text" size="20" name="dtfrom" id="dtfrom" value="2025-04-25"
											onfocus="load_calader('dtfrom')" class="text_purchase3">
									</td>
									<td>
										<div id="dateto_name">
											<input type="text" class="label_purchase" value="To" disabled="disabled">
										</div>
									</td>
									<td>
										<div id="dateto">
											<input type="text" size="20" name="dtto" id="dtto" value="2025-04-25"
												onfocus="load_calader('dtto')" class="text_purchase3">
										</div>
									</td>
									<td width="109"></td>
									<td width="86">&nbsp;</td>
								</tr>
							</tbody>
						</table>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td align="left">&nbsp;</td>
				<td align="left">&nbsp;</td>
				<td colspan="2" align="left">&nbsp;</td>
			</tr>
			<tr>
				<td width="392" align="left">&nbsp;</td>
				<td width="314" align="left"></td>
				<td colspan="2" align="left">&nbsp;</td>
			</tr>
			<tr>
				<td width="392" align="left"></td>
				<td><input type="submit" name="button" id="button" value="View" class="btn_purchase1"></td>
			</tr>
		</tbody>
	</table>
</form>