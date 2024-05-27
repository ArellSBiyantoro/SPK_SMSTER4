document.getElementById('decisionForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const name = document.getElementById('name').value;
    const tiu = document.getElementById('tiu').value;
    const twk = document.getElementById('twk').value;
    const tkp = document.getElementById('tkp').value;
    const method = document.getElementById('method').value;

    const response = await fetch('calculate.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `name=${name}&tiu=${tiu}&twk=${twk}&tkp=${tkp}&method=${method}`
    });

    const result = await response.json();
    document.getElementById('results').innerText = `Name: ${result.name}, Accepted: ${result.accepted ? 'Yes' : 'No'}`;
});