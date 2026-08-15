import Alpine from "alpinejs";
window.Alpine = Alpine;
Alpine.start();

import ApexCharts from "apexcharts";
window.ApexCharts = ApexCharts;

import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
window.flatpickr = flatpickr;

import "../css/app.css";

// Initialize components on DOM ready
document.addEventListener("DOMContentLoaded", () => {
    // Chart imports
    if (document.querySelector("#chartOne")) {
        import("./components/chart/chart-1").then((module) =>
            module.initChartOne(),
        );
    }
    if (document.querySelector("#chartTwo")) {
        import("./components/chart/chart-2").then((module) =>
            module.initChartTwo(),
        );
    }
    if (document.querySelector("#chartThree")) {
        import("./components/chart/chart-3").then((module) =>
            module.initChartThree(),
        );
    }
    if (document.querySelector("#chartSix")) {
        import("./components/chart/chart-6").then((module) =>
            module.initChartSix(),
        );
    }
    if (document.querySelector("#chartEight")) {
        import("./components/chart/chart-8").then((module) =>
            module.initChartEight(),
        );
    }
    if (document.querySelector("#chartThirteen")) {
        import("./components/chart/chart-13").then((module) =>
            module.initChartThirteen(),
        );
    }
});


//PEMINJAMAN//
//select matkul
function fetchMatkul(query) {
    const div = document.getElementById("results_matkul");
    if (!div) return;

    if (query.length < 3) {
        div.innerHTML = "";
        div.classList.add("hidden");
        return;
    }

    fetch(`${APP_URL.matkulSearch}?q=${encodeURIComponent(query)}`)
        .then((res) => res.json())
        .then((data) => {
            if (data.length === 0) {
                div.innerHTML = "";
                div.classList.add("hidden");
                return;
            }
            div.innerHTML = data
                .map(
                    (m) => `
                <div onclick="selectMatkul('${m.replace(/'/g, "\\'")}')">
                    ${m}
                </div>
            `,
                )
                .join("");
            div.classList.remove("hidden");
        })
        .catch((err) => {
            console.error("Gagal memuat data mata kuliah!", err);
            div.innerHTML = "";
            div.classList.add("hidden");
        }
    );
}
function selectMatkul(nama) {
    document.getElementById("brw_mkul").value = nama;
    document.getElementById("results_matkul").innerHTML = "";
}

//IF region E = kartu show
function toggle_PACE(region) {
    if (region !== "E") {
        document.getElementById("PACE").style.display = "none";
        document.getElementById("search_pac").value = "";
        document.getElementById("kode_pac").value = "";
        document.getElementById("search_pac").readOnly = false;
    }
    const room = document.getElementById("brw_room");
    room.value = "";
}

//inputan room === region, inc. max input
function checkRoom(input) {
    const room = input.value.trim().toUpperCase();
    const region = document.getElementById("brw_region").value;

    if (!region) {
        input.value = "";
        alert("Harap pilih lokasi kampus terlebih dulu!");
        return;
    }
    const prefixMap = {
        D: "D",
        E: "E",
        G: "G",
        F8: "F8",
    };

    const prefix = prefixMap[region];
    const isValid = prefix ? room.startsWith(prefix) : false;
    const isError = room.length > 0 && !isValid;

    input.classList.toggle("border-red-600", isError);
    input.classList.toggle("focus:ring-red-600", isError);
    input.classList.toggle("focus:border-red-600", isError);
}
function maxRoom() {
    const region = document.getElementById("brw_region").value;
    const roomInput = document.getElementById("brw_room");

    if (!roomInput) return;
    const maxLength = region === "F8" ? 5 : 4;
    roomInput.setAttribute("maxlength", maxLength);
}

//inputan dosen === alfabet
function checkDosen(input) {
    const dosen = input.value.trim();
    const isValid = dosen.length === 0 || /^[A-Za-z]/.test(dosen);
    const isError = dosen.length > 0 && !isValid;

    input.classList.toggle("border-red-600", isError);
    input.classList.toggle("focus:ring-red-600", isError);
    input.classList.toggle("focus:border-red-600", isError);
}

//inputan kelas === 4K
function checkKelas(input) {
    const kelas = input.value.trim().toUpperCase();
    const isValid =
        /^[1-5]/.test(kelas) &&
        (kelas.length < 2 || /^[A-Z]$/.test(kelas.charAt(1)));
    const isError = kelas.length > 0 && !isValid;

    input.classList.toggle("border-red-600", isError);
    input.classList.toggle("focus:ring-red-600", isError);
    input.classList.toggle("focus:border-red-600", isError);
}

//inputan jamkul === 1/...
function checkJamkul(input) {
    const jamkul = input.value.trim();
    if (/^[1-9]\//.test(jamkul)) {
        input.setAttribute("maxlength", "7");
    } else {
        input.setAttribute("maxlength", "8");
        // input.setAttribute("maxlength", "11"); //IF kelas malam can 4 hours
    }

    const jamkulPattern = /^(1[0-4]|[1-9])(\/(1[0-4]|[1-9])){0,3}$/;
    const isValid = jamkul === "" || jamkulPattern.test(jamkul);
    const isError = jamkul.length > 0 && !isValid;

    input.classList.toggle("border-red-600", isError);
    input.classList.toggle("focus:ring-red-600", isError);
    input.classList.toggle("focus:border-red-600", isError);
}

//inputan no hp 10>= number <= 12
function checkNo_HP(input) {
    let value = input.value.replace(/\D/g, "");
    if (value.startsWith("628")) {
        value = value.substring(2);
    } else if (value.startsWith("08")) {
        value = value.substring(1);
    }

    let formatted = "";
    if (value.length > 0) formatted = value.substring(0, 3);
    if (value.length > 3) formatted += "-" + value.substring(3, 7);
    if (value.length > 7) formatted += "-" + value.substring(7, 12);
    input.value = formatted;

    const rawDigits = formatted.replace(/\D/g, "");
    const isValid =
        /^8/.test(rawDigits) &&
        (rawDigits.length < 10 || /^[0-9]{10,12}$/.test(rawDigits));
    const isError = rawDigits.length > 0 && !isValid;

    const wrapper = input.parentElement;
    if (wrapper) {
        wrapper.classList.toggle("border-red-600", isError);
        wrapper.classList.toggle("focus-within:border-red-600", isError);
        wrapper.classList.toggle("focus-within:ring-red-600", isError);
        if (isError) {
            wrapper.classList.remove("border-gray-200", "dark:border-gray-800");
        } else {
            wrapper.classList.add("border-gray-200", "dark:border-gray-800");
        }
    }
}

//autoselect PAC
function autolockPAC() {
    const roomInput = document.getElementById("brw_room");
    const room = roomInput.value.trim().toUpperCase();
    const location = document.getElementById("brw_region").value;
    const region = document.getElementById("brw_region");
    const PACE = document.getElementById("PACE");

    if (
        location !== "E" ||
        (!room.startsWith("E2") && !room.startsWith("E3"))
    ) {
        PACE.style.display = "none";
        document.getElementById("search_pac").value = "";
        document.getElementById("kode_pac").value = "";
        return;
    }

    PACE.style.display = "block";
    fetch(
        `${APP_URL.brg_search}?type=PAC&location=${location}&query=${encodeURIComponent(room)}`,
    )
        .then((res) => res.json())
        .then((data) => {
            const match = data.find(
                (item) =>
                    item.brg_kode.toUpperCase().includes(room) ||
                    item.brg_name
                        .toUpperCase()
                        .replace(/[^A-Z0-9]/g, "")
                        .includes(room.replace(/[^A-Z0-9]/g, "")),
            );

            if (match) {
                brg_select("PAC", match.brg_id, `${match.brg_name}`);
                document.getElementById("search_pac").readOnly = true;
            } else {
                document.getElementById("search_pac").value =
                    "Kartu tidak ditemukan";
                document.getElementById("kode_pac").value = "";
                document.getElementById("search_pac").readOnly = true;
            }
        })
        .catch((err) => console.error("Gagal memuat data kartu ruangan!", err));
}

//search barang
function brg_search(type, query) {
    const location = document.getElementById("brw_region").value;
    if (!location) {
        alert("Harap pilih lokasi kampus terlebih dulu!");
        return;
    }

    const resultId = "results_" + type.toLowerCase();
    const div = document.getElementById(resultId);
    if (!div) return;
    const triggerMap = {
        VWR: "P-",
        HDMI: "HDMI",
        PAC: "",
    };

    const prefix = triggerMap[type] ?? "";
    if (prefix && !query.toUpperCase().startsWith(prefix.toUpperCase())) {
        div.innerHTML = "";
        div.classList.add("hidden");
        return;
    }

    fetch(`${APP_URL.brg_search}?type=${type}&location=${location}&query=${encodeURIComponent(query)}`,)
        .then((res) => res.json())
        .then((data) => {
            if (data.length === 0) {
                div.innerHTML = `<div class="cursor-default text-gray-500">Tidak ada barang tersedia.</div>`;
                div.classList.remove("hidden");
                return;
            }

            div.innerHTML = data
                .map(
                    (item) => `
                        <div onclick="brg_select('${type}', '${item.brg_id}', '${item.brg_name}')">
                            ${item.brg_name} (${item.brg_kode})
                        </div>
                    `,
                )
                .join("");
            div.classList.remove("hidden");
        })
        .catch((err) => {
            console.error("Gagal memuat data perangkat!", err);
            div.innerHTML = "";
            div.classList.add("hidden");
        }
    );
}

//select barang
function brg_select(type, id, label) {
    const inputId = "search_" + type.toLowerCase();
    const hiddenId = "kode_" + type.toLowerCase();
    const resultId = "results_" + type.toLowerCase();

    document.getElementById(inputId).value = label;
    document.getElementById(hiddenId).value = id;
    document.getElementById(resultId).innerHTML = "";
}

//cancel select barang
function brg_reset(type) {
    const inputId = "search_" + type.toLowerCase();
    const hiddenId = "kode_" + type.toLowerCase();
    const resultId = "results_" + type.toLowerCase();

    document.getElementById(inputId).value = "";
    document.getElementById(hiddenId).value = "";
    document.getElementById(resultId).innerHTML = "";
}

//return barang
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-acc").forEach((btn) => {
        btn.addEventListener("click", function () {
            const id = this.dataset.id;
            if (!confirm("ACC peminjaman ini?")) return;

            fetch(`/peminjaman/acc/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                    "Content-Type": "application/json",
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch((err) => {
                    console.error("Gagal memproses peminjaman ruangan!", err);
                    alert("Terjadi kesalahan saat memproses data pengajuan.");
                });
        });
    });
    document
        .querySelectorAll(".btn-selesai")
        .forEach((btn) => attachSelesai(btn));
});
function attachSelesai(btn) {
    btn.addEventListener("click", function () {
        const id = this.dataset.id;
        if (!confirm("Selesaikan peminjaman ini?")) return;

        fetch(`/peminjaman/selesai/${id}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                "Content-Type": "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch((err) => {
                console.error("Gagal menyelesaikan peminjaman perangkat!", err);
                alert("Terjadi kesalahan saat memproses data pengajuan.");
            });
    });
}

//edit all barang
function toggleEdit(id) {
    const viewOnlyFields = ["kode", "nama", "status"];
    const isEditing =
        document.getElementById("nama-edit-" + id).style.display !== "none";

    viewOnlyFields.forEach((field) => {
        document.getElementById(field + "-view-" + id).style.display = isEditing
            ? "inline"
            : "none";
        document.getElementById(field + "-edit-" + id).style.display = isEditing
            ? "none"
            : "inline-block";
    });

    const lokasiEl = document.getElementById("lokasi-edit-" + id);
    if (lokasiEl) {
        lokasiEl.style.display = isEditing ? "none" : "inline-block";
    }

    document.getElementById("btn-edit-" + id).style.display = isEditing
        ? "inline-block"
        : "none";
    document.getElementById("btn-simpan-" + id).style.display = isEditing
        ? "none"
        : "inline-block";
    document.getElementById("btn-batal-" + id).style.display = isEditing
        ? "none"
        : "inline-block";

    const btnHapus = document.getElementById("btn-hapus-" + id);
    if (btnHapus) {
        btnHapus.closest("form").style.display = isEditing
            ? "inline-block"
            : "none";
    }
}

//copy OTP
function copyOTP() {
    const code = document.querySelector(".code-box").textContent.trim();
    const textToCopy = "OTP Peminjaman Perangkat Anda: " + code;

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard
            .writeText(textToCopy)
            .then(() => {
                alert("Kode OTP berhasil disalin!");
            })
            .catch((err) => {
                console.error("Gagal menyalin OTP!", err);
                alert("Gagal menyalin kode OTP, silakan salin manual.");
            });
    } else {
        const el = document.createElement("textarea");
        el.value = textToCopy;
        document.body.appendChild(el);
        el.select();
        document.execCommand("copy");
        document.body.removeChild(el);
        alert("Kode OTP berhasil disalin!");
    }
}

//modal open acc
function confirmPeminjaman(id, mode) {
    const url = mode === 'QUEUED' ? `/peminjaman/acc/${id}` : `/peminjaman/selesai/${id}`;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch((err) => {
            console.error('Gagal memproses peminjaman:', err);
            alert('Terjadi kesalahan saat memproses data pengajuan.');
        }
    );
}


//PEMINDAHAN//
//load data matkul
document.addEventListener("DOMContentLoaded", function () {
    const namaDosen = document.getElementById("nama")?.value;
    if (!namaDosen || typeof APP_URL === "undefined" || !APP_URL.jadwalDosen)
        return;

    fetch(`${APP_URL.jadwalDosen}?nama=${encodeURIComponent(namaDosen)}`)
        .then((res) => res.json())
        .then((data) => {
            // console.log("jadwal:", data);
            const select = document.getElementById("mv_mkul");
            if (!select) return;

            if (data.length === 0) {
                select.innerHTML =
                    "<option disabled>Tidak ada jadwal ditemukan</option>";
                return;
            }

            data.forEach((item) => {
                const opt = document.createElement("option");
                opt.value = JSON.stringify(item);
                opt.text = `${item.sch_mkul} | ${item.sch_day} jam ${item.jam_grup} | ${item.sch_kelas} | ${item.room_name}`;
                select.appendChild(opt);
            });
        })
        .catch((err) => console.error("Gagal memuat jadwal dosen!", err));
});

//sel matkul -> hidden fields
function onMatkulChange(select) {
    const item = JSON.parse(select.value);
    document.getElementById("mv_mkul_val").value = item.sch_mkul;
    document.getElementById("room_id").value = item.room_id;
    document.getElementById("mv_kelas").value = item.sch_kelas;
    document.getElementById("mv_jamkul").value = item.jam_grup;
    document.getElementById("mv_tgl_mkul").value = item.tanggal;
    document.getElementById("mv_region").selectedIndex = 0;

    resetSelect("mv_gd", true);
    resetSelect("mv_floor", true);
    resetSelect("room_replace", true);
}

//sel region -> load gedung
function onRegionChange(region) {
    const hari = getMatkul()?.sch_day;
    const jam = document.getElementById("mv_jamkul").value;

    resetSelect("mv_gd", false, "Pilih gedung");
    resetSelect("mv_floor", true, "Pilih lantai");
    resetSelect("room_replace", true, "Pilih ruangan");

    if (!hari || !jam || !region) return;
    fetch(`${APP_URL.ruanganKosong}?hari=${hari}&jam=${jam}&region=${region}`)
        .then((res) => res.json())
        .then((data) => {
            const select = document.getElementById("mv_gd");
            const gedungList = [...new Set(data.map((r) => r.room_gd))].sort();

            if (gedungList.length === 0) {
                select.innerHTML =
                    "<option disabled>Tidak ada gedung tersedia</option>";
                return;
            }

            gedungList.forEach((gd) => {
                const opt = document.createElement("option");
                opt.value = gd;
                opt.text = `Gedung ${gd}`;
                select.appendChild(opt);
            });
            select.dataset.ruangan = JSON.stringify(data);
        })
        .catch((err) =>
            console.error("Gagal memuat data ruangan kosong!", err),
        );
}

//sel gedung -> load lantai
function onGedungChange(gd) {
    const data = JSON.parse(
        document.getElementById("mv_gd").dataset.ruangan || "[]",
    );
    const filtered = data.filter((r) => r.room_gd === gd);
    resetSelect("mv_floor", false);
    resetSelect("room_replace", true);
    const lantaiList = [...new Set(filtered.map((r) => r.room_floor))].sort();

    if (lantaiList.length === 0) return;
    const select = document.getElementById("mv_floor");
    lantaiList.forEach((fl) => {
        const opt = document.createElement("option");
        opt.value = fl;
        opt.text = `Lantai ${fl}`;
        select.appendChild(opt);
    });
    select.dataset.ruangan = JSON.stringify(filtered);
}

//sel lantai -> load ruangan kosong
function onLantaiChange(floor) {
    const data = JSON.parse(
        document.getElementById("mv_floor").dataset.ruangan || "[]",
    );
    const filtered = data.filter((r) => r.room_floor === floor);
    resetSelect("room_replace", false);

    if (filtered.length === 0) {
        const select = document.getElementById("room_replace");
        select.innerHTML =
            "<option disabled>Tidak ada ruangan tersedia</option>";
        return;
    }

    const select = document.getElementById("room_replace");
    filtered.forEach((r) => {
        const opt = document.createElement("option");
        opt.value = r.room_id;
        opt.text = `${r.room_name} (Jam kosong: ${r.jam_grup})`;
        select.appendChild(opt);
    });
}

//reset dropdown
function resetSelect(id, disable = false, label = "Pilih") {
    const select = document.getElementById(id);
    if (!select) return;
    select.innerHTML = `<option value='' disabled selected>${label}</option>`;
    select.disabled = disable;
    delete select.dataset.ruangan;
}

//get matkul terpilih
function getMatkul() {
    const select = document.getElementById("mv_mkul");
    if (!select?.value) return null;
    try {
        return JSON.parse(select.value);
    } catch {
        return null;
    }
}

//update pemindahan
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-pindah")) {
        const id = e.target.dataset.id;
        const aksi = e.target.dataset.aksi;

        fetch("/pemindahan/update", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
            body: JSON.stringify({ id_p: id, aksi: aksi }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) location.reload();
            })
            .catch((err) => {
                console.error("Gagal memproses pemindahan ruangan!", err);
                alert("Terjadi kesalahan saat memproses data pengajuan.");
            });
    }
});

//edit + update ruangan
let currentRoomSchedule = [];
let selectedCell = null;
let activeRoomBtn = null;
function loadRoomSchedule(roomName) {
    document.getElementById("room-title-admin").textContent =
        "Ruangan " + roomName;
    document.getElementById("nama_ruang").value = roomName;
    selectedCell = null;

    document.querySelectorAll(".jadwal-cell").forEach((cell) => {
        cell.classList.remove(
            "bg-success-300",
            "dark:bg-success-500/40",
            "bg-success-400",
            "bg-warning-500",
            "dark:bg-warning-500/40",
        );
        cell.classList.add("bg-white", "dark:bg-transparent");
        cell.dataset.dosen = "";
        cell.dataset.matkul = "";
        cell.dataset.kelas = "";
        cell.dataset.status = "READY";
    });
    fetch(`${APP_URL.roomSchedule}?room=${encodeURIComponent(roomName)}`)
        .then((res) => res.json())
        .then((data) => {
            currentRoomSchedule = data;
            data.forEach((item) => {
                const cellId = `${item.sch_day}_${item.sch_jamkul}`;
                const cell = document.getElementById(cellId);
                if (!cell) return;

                cell.dataset.status = item.room_status ?? "READY";
                cell.dataset.dosen = item.sch_dosen ?? "";
                cell.dataset.matkul = item.sch_mkul ?? "";
                cell.dataset.kelas = item.sch_kelas ?? "";

                if (item.room_status === "USED") {
                    cell.classList.remove("bg-white", "dark:bg-transparent");
                    cell.classList.add(
                        "bg-success-400",
                        "dark:bg-success-500/40",
                    );
                }
            });
            document.getElementById("hari_jadwal").value = "";
            document.getElementById("jam_jadwal").value = "";
            document.getElementById("isFilled_jadwal").value = "";
            document.getElementById("dosen_jadwal").value = "";
            document.getElementById("matkul_jadwal").value = "";
            document.getElementById("kelas_jadwal").value = "";
        })
        .catch((err) => console.error("Gagal memuat jadwal ruangan:", err));
}
function restoreCellColor(cell) {
    cell.classList.remove("bg-warning-500", "dark:bg-warning-500/40");
    if (cell.dataset.status === "USED") {
        cell.classList.remove("bg-white", "dark:bg-transparent");
        cell.classList.add("bg-success-400", "dark:bg-success-500/40");
    } else {
        cell.classList.remove("bg-success-400", "dark:bg-success-500/40");
        cell.classList.add("bg-white", "dark:bg-transparent");
    }
}
function descJadwal(cellId) {
    const cell = document.getElementById(cellId);
    if (!cell) return;

    if (selectedCell && selectedCell !== cell) {
        restoreCellColor(selectedCell);
    }

    cell.classList.remove(
        "bg-white",
        "dark:bg-transparent",
        "bg-success-400",
        "dark:bg-success-500/40",
    );
    cell.classList.add("bg-warning-500", "dark:bg-warning-500/40");
    selectedCell = cell;

    const [hari, jam] = cellId.split("_");
    document.getElementById("hari_jadwal").value = hari.toUpperCase();
    document.getElementById("jam_jadwal").value = APP_URL.listJam[jam] ?? jam;
    document.getElementById("isFilled_jadwal").value =
        cell.dataset.status || "READY";
    document.getElementById("dosen_jadwal").value = (
        cell.dataset.dosen || ""
    ).toUpperCase();
    document.getElementById("matkul_jadwal").value = cell.dataset.matkul || "";
    document.getElementById("kelas_jadwal").value = cell.dataset.kelas || "";
}
function clearRoomSchedule() {
    document.getElementById("room-title-admin").textContent = "Ruangan";
    document.getElementById("nama_ruang").value = "";
    selectedCell = null;

    document.querySelectorAll(".jadwal-cell").forEach((cell) => {
        cell.classList.remove(
            "bg-success-300",
            "dark:bg-success-500/40",
            "bg-success-400",
            "bg-warning-500",
            "dark:bg-warning-500/40",
        );
        cell.classList.add("bg-white", "dark:bg-transparent");
        cell.dataset.dosen = "";
        cell.dataset.matkul = "";
        cell.dataset.kelas = "";
        cell.dataset.status = "READY";
    });

    document.getElementById("hari_jadwal").value = "";
    document.getElementById("jam_jadwal").value = "";
    document.getElementById("isFilled_jadwal").value = "";
    document.getElementById("dosen_jadwal").value = "";
    document.getElementById("matkul_jadwal").value = "";
    document.getElementById("kelas_jadwal").value = "";
}
function setButtonActive(btnEl) {
    // >>> ATUR WARNA AKTIF DI SINI <
    btnEl.classList.remove("border-gray-200", "text-gray-600", "dark:border-gray-700", "dark:text-gray-400");
    btnEl.classList.add("bg-blue-light-700", "text-white");
}
function resetButtonStyle(btnEl) {
    // >>> WARNA SEMULA (default) <
    btnEl.classList.remove("bg-blue-light-700", "text-white");
    btnEl.classList.add("border-gray-200", "text-gray-600", "dark:border-gray-700", "dark:text-gray-400");
}
function toggleRoomSchedule(roomName, btnEl) {
    const isSameRoom = activeRoomBtn === btnEl;

    if (activeRoomBtn && activeRoomBtn !== btnEl) {
        resetButtonStyle(activeRoomBtn);
    }

    if (isSameRoom) {
        clearRoomSchedule();
        resetButtonStyle(btnEl);
        activeRoomBtn = null;
        sessionStorage.removeItem("activeRoom");
        return;
    }

    setButtonActive(btnEl);
    activeRoomBtn = btnEl;
    sessionStorage.setItem("activeRoom", roomName);
    loadRoomSchedule(roomName);

    document.getElementById("grid_cld").scrollIntoView({
        behavior: "smooth",
        block: "center",
    });
}
document.addEventListener("DOMContentLoaded", function () {
    const savedRoom = sessionStorage.getItem("activeRoom");
    if (savedRoom) {
        const btn = document.querySelector(`.btn-detail-room[data-room="${savedRoom}"]`);
        if (btn) {
            setButtonActive(btn);
            activeRoomBtn = btn;
            loadRoomSchedule(savedRoom);
        }
    }
});

//modal open acc/dec
function confirmPemindahan(id, aksi) {
    fetch('/pemindahan/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ id_p: id, aksi: aksi }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) location.reload();
        })
        .catch((err) => {
            console.error('Gagal memproses pemindahan:', err);
            alert('Terjadi kesalahan saat memproses permintaan.');
        });
}


//ADDITION//
//time GMT+7 + greet
function getSelamat(hour) {
    if (hour >= 6 && hour <= 10) return "Selamat Pagi";
    if (hour > 10 && hour < 15) return "Selamat Siang";
    if (hour >= 15 && hour < 18) return "Selamat Sore";
    if (hour >= 18 && hour < 23) return "Selamat Malam";
    return "-Inactive-";
}
function runTime() {
    const greetingEl = document.getElementById("selamat");
    const timeEl = document.getElementById("time");
    if (!timeEl) return;

    const now = new Date();
    const day = [
        "Minggu",
        "Senin",
        "Selasa",
        "Rabu",
        "Kamis",
        "Jumat",
        "Sabtu",
    ];
    const mo = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];
    const daynow = day[now.getDay()];
    const date = now.getDate();
    const monow = mo[now.getMonth()];
    const year = now.getFullYear();

    let hour = now.getHours().toString().padStart(2, "0");
    let min = now.getMinutes().toString().padStart(2, "0");
    let sec = now.getSeconds().toString().padStart(2, "0");

    const dateTime = `${daynow}, ${date} ${monow} ${year} | ${hour}:${min}:${sec}`;
    document.getElementById("time").innerHTML = dateTime;

    if (greetingEl) greetingEl.textContent = getSelamat(hour);
}
setInterval(runTime, 1000);
runTime();

//remember uname auth
const usernameInput = document.getElementById("username");
if (usernameInput) {
    const savedUsername = localStorage.getItem("saved_username");
    if (savedUsername) {
        usernameInput.value = savedUsername;
    }
    usernameInput.addEventListener("input", function () {
        const value = usernameInput.value.trim();

        if (value !== "") {
            localStorage.setItem("saved_username", value);
        } else {
            localStorage.removeItem("saved_username");
        }
    });
}

//view password auth
function viewPass() {
    const pw = document.getElementById("password");
    pw.type = pw.type === "password" ? "text" : "password";
}

//dropdown close banyakan
document.addEventListener("click", function (e) {
    const configs = [
        { input: "brw_mkul", results: "results_matkul" },
        { input: "search_hdmi", results: "results_hdmi" },
        { input: "search_vwr", results: "results_vwr" },
    ];

    configs.forEach(({ input, results }) => {
        const inputEl = document.getElementById(input);
        const resultsEl = document.getElementById(results);

        if (!inputEl || !resultsEl) return;
        if (!inputEl.contains(e.target) && !resultsEl.contains(e.target)) {
            resultsEl.innerHTML = "";
            resultsEl.classList.add("hidden");
        }
    });
});

//GRAFIK LAPORAN
window.initChartTren = function (config) {
    const { bulanLabel, dataPeminjamanChart, dataPemindahanChart, 
        mingguLabel, dataPeminjamanChartMingguan, dataPemindahanChartMingguan } = config;
    const dataBulanan = {
        categories: bulanLabel,
        peminjaman: dataPeminjamanChart,
        pemindahan: dataPemindahanChart,
    };
    const dataMingguan = {
        categories: mingguLabel,
        peminjaman: dataPeminjamanChartMingguan,
        pemindahan: dataPemindahanChartMingguan,
    };

    const isDark = document.documentElement.classList.contains('dark');
    const baseOptions = {
        chart: {
            type: 'line',
            height: 300,
            toolbar: { show: true },
            fontFamily: 'inherit',
            animations: {
                enabled: true,
                dynamicAnimation: { enabled: true },
            },
        },
        stroke: {
            curve: 'smooth',
            width: 3,
        },
        markers: {
            size: 5,
            strokeWidth: 2,
            hover: { size: 7 },
        },
        colors: ['#465fff', '#12b76a'],
        dataLabels: { enabled: false },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            fontSize: '15px',
        },
        grid: {
            borderColor: isDark ? '#1f2937' : '#e5e7eb',
        },
        xaxis: {
            categories: dataBulanan.categories,
            labels: {
                style: {
                    fontSize: '13px',
                },
            },
        },
        yaxis: {
            labels: {
                style: {
                    fontSize: '15px',
                },
            },
        },
        tooltip: {
            style: {
                fontSize: '14px',
            },
        },
    };

    const container = document.querySelector("#chartTren");
    const chart = new ApexCharts(container, {
        ...baseOptions,
        series: [
            { name: 'Peminjaman', data: dataBulanan.peminjaman },
            { name: 'Pemindahan', data: dataBulanan.pemindahan },
        ],
    });
    chart.render();

    requestAnimationFrame(() => {
        chart.windowResizeHandler ? chart.windowResizeHandler() : window.dispatchEvent(new Event('resize'));
    });

    window.switchTren = function (mode) {
        const source = mode === 'minggu' ? dataMingguan : dataBulanan;
        chart.updateOptions({
            xaxis: { categories: source.categories },
            series: [
                { name: 'Peminjaman', data: source.peminjaman },
                { name: 'Pemindahan', data: source.pemindahan },
            ],
        }, false, true);
    };
};

//SET REFRESH PAGE + STAY CURR.SCROLL POS
function saveScrollPos() {
    sessionStorage.setItem("scrollPos", window.scrollY);
}
function refreshPage() {
    saveScrollPos();
    location.reload();
}
document.addEventListener("submit", function (e) {
    saveScrollPos();
});
document.addEventListener("DOMContentLoaded", function () {
    const savedScroll = sessionStorage.getItem("scrollPos");
    if (savedScroll !== null) {
        window.scrollTo(0, parseInt(savedScroll));
        sessionStorage.removeItem("scrollPos");
    }
});

//landing CTA -> cta
const heroBtn = document.getElementById("btn_CTA");
const navBtn = document.getElementById("btn_Log");
heroBtn?.addEventListener("click", () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
    navBtn.classList.add("login-highlight");
    setTimeout(() => {
        window.location.href = navBtn.href;
    }, 1200);
});

//block mobile on admin view
function checkScreenSize() {
    const overlay = document.getElementById("screen-block-overlay");
    if (!overlay) return;

    const MIN_WIDTH = 1024;
    const isMobileUA = /Android|iPhone|iPad|iPod|Mobile|Windows Phone/i.test(navigator.userAgent);
    const isBlocked = isMobileUA || window.innerWidth < MIN_WIDTH;

    if (isBlocked) {
        overlay.style.display = "flex";
        document.body.style.overflow = "hidden";
    } else {
        overlay.style.display = "none";
        document.body.style.overflow = "";
    }
}

checkScreenSize();
document.addEventListener("DOMContentLoaded", checkScreenSize);
window.addEventListener("resize", checkScreenSize);
window.addEventListener("orientationchange", checkScreenSize);


window.refreshPage = refreshPage;
window.fetchMatkul = fetchMatkul;
window.selectMatkul = selectMatkul;
window.toggle_PACE = toggle_PACE;
window.checkRoom = checkRoom;
window.maxRoom = maxRoom;
window.checkDosen = checkDosen;
window.checkKelas = checkKelas;
window.checkJamkul = checkJamkul;
window.checkNo_HP = checkNo_HP;
window.autolockPAC = autolockPAC;
window.brg_search = brg_search;
window.brg_select = brg_select;
window.brg_reset = brg_reset;
window.toggleEdit = toggleEdit;
window.copyOTP = copyOTP;
window.onMatkulChange = onMatkulChange;
window.onRegionChange = onRegionChange;
window.onGedungChange = onGedungChange;
window.onLantaiChange = onLantaiChange;
window.resetSelect = resetSelect;
window.getMatkul = getMatkul;
window.viewPass = viewPass;
window.loadRoomSchedule = loadRoomSchedule;
window.descJadwal = descJadwal;
window.toggleRoomSchedule = toggleRoomSchedule;
window.confirmPemindahan = confirmPemindahan;
window.confirmPeminjaman = confirmPeminjaman;
window.checkScreenSize = checkScreenSize;

