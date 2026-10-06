import './bootstrap';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import 'datatables.net-dt';


window.Alpine = Alpine;
window.Swal = Swal; // ✅ Make Swal globally accessible

Alpine.start();

