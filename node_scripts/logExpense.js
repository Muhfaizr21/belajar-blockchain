require('dotenv').config();
const { ethers } = require('ethers');

async function main() {
  const hashData = process.argv[2];
  if (!hashData) {
    console.error("❌ Missing hash parameter");
    process.exit(1);
  }

  const provider = new ethers.providers.JsonRpcProvider(process.env.RPC_URL);
  const wallet = new ethers.Wallet(process.env.PRIVATE_KEY, provider);
  const contract = new ethers.Contract(process.env.CONTRACT_ADDRESS, require('./ExpenseLedgerABI.json'), wallet);

  try {
    const tx = await contract.logExpense(hashData);
    console.log(tx.hash);
  } catch (err) {
    console.error("Blockchain Error:", err.message);
  }
}
main();
