	/*
	*/
	var months_el = ['Γενάρη', 'Φλεβάρη', 'Μάρτη', 'Απρίλη', 'Μάη', 'Ιούνη', 'Ιούλη', 'Αυγούστου', 'Σεπτέμβρη', 'Οκτώβρη', 'Νοέμβρη', 'Δεκέμβρη'];
	var months_en = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	function mydate_e1()
	{
		var input_1 = document.getElementById("customer_care_maintenance_date");
		if (input_1)
		{
			/* console.log(input_1.value); */
			date_1 = new Date(document.getElementById("customer_care_maintenance_date").value);
			if (date_1)
			{
				day_1 = date_1.getDate();
				month_1 = months[date_1.getMonth()];
				year_1 = date_1.getFullYear();
				document.getElementById("customer_care_ndt_1").innerHTML = day_1 + " " + month_1 + " " + year_1;
			}
			else
			{
				document.getElementById("customer_care_ndt_1").innerHTML = _e('Undefined date', 'customer_care');
			}
		}		
	}	

	function mydate_e2()
	{
		var input_2 = document.getElementById("customer_care_payment_date");
		if (input_2)
		{
			/* console.log(input_2.value); */
			date_2 = new Date(document.getElementById("customer_care_payment_date").value);
			if (date_2)
			{
				day_2 = date_2.getDate();
				month_2 = months[date_2.getMonth()];
				year_2 = date_2.getFullYear();
				document.getElementById("customer_care_ndt_2").innerHTML = day_2 + " " + month_2 + " " + year_2;
			}
			else
			{
				document.getElementById("customer_care_ndt_2").innerHTML = _e('Undefined date', 'customer_care');
			}
		}
	}
	
	mydate_e1();
	mydate_e2();