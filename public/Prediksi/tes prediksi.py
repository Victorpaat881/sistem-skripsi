import requests
import json

data_points = [
    {
    "STOK AWAL": 5000,
    "PENERIMAAN": 5000,
    "PERSEDIAAN": 10000,
    "PEMAKAIAN": 1000,
    "SISA STOK": 9000,
    "STOK OPTIMUM": 1700,
    "NAMA OBAT": "Ambroxol Tablet",
    "SATUAN": "Botol"
    },
    {
    "STOK AWAL": 1200,
    "PENERIMAAN": 4000,
    "PERSEDIAAN": 2000,
    "PEMAKAIAN": 5000,
    "SISA STOK": 20000,
    "STOK OPTIMUM": 12100,
    "NAMA OBAT": "zinc",
    "SATUAN": "BTL"
    },
    {
    "STOK AWAL": 6200,
    "PENERIMAAN": 5000,
    "PERSEDIAAN": 2000,
    "PEMAKAIAN": 43000,
    "SISA STOK": 5000,
    "STOK OPTIMUM": 4200,
    "NAMA OBAT": "vagial",
    "SATUAN": "Tablet"
    }
]

# Baca struktur kolom one-hot encoding
# Jika disimpan dalam variabel
# one_hot_columns = [...]
# Jika disimpan dalam file JSON
#with open('one_hot_columns.json', 'r') as f:
#    one_hot_columns = json.load(f)

# Build test data dynamically
#def build_test_data(input_data, one_hot_columns):
#    test_data = {col: 0 for col in one_hot_columns}
#
#    for key, value in input_data.items():
#        col_name = f"{key}_{value}"
#        if col_name in one_hot_columns:
#            test_data[col_name] = 1

#   for non_one_hot_col in ['STOK AWAL', 'PENERIMAAN', 'PERSEDIAAN', 'PEMAKAIAN', 'SISA STOK', 'STOK OPTIMUM']:
#        test_data[non_one_hot_col] = input_data[non_one_hot_col]

#    return test_data

# Build test data
#test_data = build_test_data(input_data, one_hot_columns)

# List to store prediction results
predictions = []

# Loop through each data point and send prediction request
for data_point in data_points:
    # Send prediction request
    url = "http://127.0.0.1:5000/predict"
    response = requests.post(url, json=data_point)

    # Check response status
    if response.status_code == 200:
        result = response.json()
        prediction = result['prediction']
        predictions.append({"prediction": prediction})
    else:
        predictions.append({"error": f"Failed to get prediction. Status code: {response.status_code}"})

# Print the results
for result in predictions:
    print(result)
