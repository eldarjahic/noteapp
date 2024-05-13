var NoteService = {
  get_notes: function () {
    RestClient.get("notes", function (raw_data) {
      console.log(raw_data);
      //const data = JSON.parse(raw_data);
      
      //const notes = data.data;
      const notes = raw_data;
      
      var notesHtml = "";
      for (var i = 0; i < notes.length; i++) {
        notesHtml += `
            <div class="col mb-5">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <h5 class="fw-bolder">${notes[i].title}</h5>
                            <p style="margin-top: 10px;">${notes[i].content}</p>
                            <button onclick="NoteService.setEditContent(${notes[i].id}, '${notes[i].title}', '${notes[i].content}')" class="btn btn-warning">Edit</button>
                            <button onclick="setDeleteNote(${notes[i].id})" type="button" class="btn btn-warning deleteButton" data-toggle="modal" data-target="#deleteModal">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
      }
      $(".row").html(notesHtml);
    });
  },
  open_edit_patient_modal: function (notes_id) {
    RestClient.get("get_patient.php?id=" + notes_id, function (data) {
      $("#add-patient-modal").modal("toggle");
      $("#add-patient-form input[name='id']").val(data.id);
      $("#add-patient-form input[name='first_name']").val(data.first_name);
      $("#add-patient-form input[name='last_name']").val(data.last_name);
      $("#add-patient-form input[name='email']").val(data.email);
      $("#add-patient-form input[name='created_at']").val(data.created_at);
    });
  },
  delete_note: function (id) {
    RestClient.delete("notes/" + id, null, function (data) {
      NoteService.get_notes();
    });
  },
  add_note: function (note) {
    RestClient.post("notes", JSON.stringify(note), function (data) {
      $("#addNoteModal").modal("hide");
      NoteService.get_notes();
    });
    var myModal = new bootstrap.Modal(document.getElementById("addNoteModal"));
    myModal.hide();
  },

  setEditContent: function (id, title, content) {
    //RestClient.post("edit_notes.php", JSON.stringify(note), function (data) {
    // Set the value of the title input and content textarea in the Edit Note modal
    $("#editNoteTitle").val(title);
    $("#editNoteContent").val(content);

    // Store the note ID in a hidden input in the Edit Note modal
    $("#editNoteId").val(id);
    // NoteService.get_notes();
    //});

    // Show the Edit Note modal
    var myModal = new bootstrap.Modal(document.getElementById("editNoteModal"));
    myModal.show();
  },
  edit_note: function (id, note) {
    RestClient.put(
      "notes/" + id,
      JSON.stringify(note),
      function (data) {
        $("#editNoteModal").modal("hide");
        NoteService.get_notes();
      }
    );
    var myModal = new bootstrap.Modal(document.getElementById("editNoteModal"));
    myModal.hide();
  },
};
